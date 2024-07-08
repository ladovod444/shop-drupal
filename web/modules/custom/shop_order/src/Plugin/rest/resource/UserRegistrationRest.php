<?php

namespace Drupal\shop_order\Plugin\rest\resource;
use Drupal\rest\Plugin\ResourceBase;
use Psr\Log\LoggerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\user\Entity\User;

/**
 * Provides a resource to get view modes by entity and bundle.
 * @RestResource(
 *   id = "user_registration_rest",
 *   label = @Translation("User Registration API"),
 *   uri_paths = {
 *     "canonical" = "/api/user-registration/{id}",
 *     "create" = "/api/user-registration",
 *   }
 * )
 */
class UserRegistrationRest extends ResourceBase {
  /**
   * A current user instance which is logged in the session.
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $loggedUser;

  /**
   * Constructs a Drupal\rest\Plugin\ResourceBase object.
   *
   * @param array $config
   *   A configuration array which contains the information about the plugin instance.
   * @param string $module_id
   *   The module_id for the plugin instance.
   * @param mixed $module_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   A currently logged user instance.
   */
//  public function __construct(array $config, $module_id, $module_definition, array $serializer_formats, LoggerInterface $logger, AccountProxyInterface $current_user) {
//    parent::__construct($config, $module_id, $module_definition, $serializer_formats, $logger);
//    $this->loggedUser = $current_user;
//  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {

    $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition);

    $instance->logger = $container->get('logger.factory')->get('user_registration_api');
    //$instance->currentUser = $container->get('current_user');
    //$instance->cartManager = $container->get('commerce_cart.cart_manager');

    return $instance;

//    return new static(
//      $config,
//      $module_id,
//      $module_definition,
//      $container->getParameter('serializer.formats'),
//      $container->get('logger.factory')->get('user_registration_api'),
//      $container->get('current_user')
//    );
  }

  /*
  * User Registration API
  */
  public function post(Request $data) {
    global $base_url;
    try {
      $content = $data->getContent();
      $params = json_decode($content, TRUE);

      //return new JsonResponse($params);

      $message_string = "";
      $message_string .= empty($params['mail']) ? "Email ID. " : "";
      $message_string .= empty($params['pass']) ? "Password. " : "";
      $message_string .= empty($params['username']) ? "Name. " : "";
      //$message_string .= empty($params['first_name']) ? "First Name. " : "";
//      $message_string .= empty($params['last_name']) ? "Last Name. " : "";
//      $message_string .= empty($params['city']) ? "City. " : "";
      if($message_string) {
        $final_api_reponse = array(
          "status" => "Error",
          "message" => "Mandatory Fields Missing",
          "result" => "Required fields: ".$message_string
        );
      }
      else {
        //$user_name = strtolower($params['username'].'.'.$params['username']);
        $user_name = strtolower($params['username']);
        //$user_full_name = ucfirst($params['first_name']).' '.ucfirst($params['last_name']);
        $user_full_name = ucfirst($params['username']);
        $user_email = $params['mail'];

        // Checking for duplicate user entries
        $email_check = \Drupal::entityQuery('user')->accessCheck(TRUE)->condition('mail', $user_email)->execute();
        $username_check = \Drupal::entityQuery('user')->accessCheck(TRUE)->condition('name', $user_name)->execute();
        if (!empty($email_check) || !empty($username_check)) {
          $final_api_reponse = array(
            "status" => "Error",
            "message" => "Registration Failed",
            "result" => "User details already exists. Please try with different Name or Email-ID."
          );
        }
        else {
          // Create new user
//          $new_user = User::create([
//            'name' => $user_name,
//            'pass' => $params['password'],
//            'mail' => $user_email,
//            //'roles' => array('general', 'authenticated'),
//            'roles' => ['authenticated'],
////            'field_first_name' => ucfirst($params['first_name']),
////            'field_last_name' => ucfirst($params['last_name']),
////            'field_city' => $params['city'],
//            'status' => 1,
//          ]);
//          $new_user->save();

          $user = User::create();
          //$user->setPassword($params['password']);
          $user->setPassword($params['pass']);
          $user->enforceIsNew();
          $user->setEmail($user_email);
          $user->setUsername($user_name);
          $user->activate();

          //return new JsonResponse($user);
          //$user->addRole('authenticated');

//Save user
          $res = $user->save();

          // AND then login user
          $user = User::load($user->id());
          user_login_finalize($user);

          $final_api_reponse = [
            "status" => "Success",
            "message" => "Registration Successful",
            "result" => "Thank You. Account for ".$user_full_name." has been created.",
            'uid' => $user->id(),
          ];
        }
      }
      return new JsonResponse($final_api_reponse);
    }
    catch(Exception $exception) {
      $this->exception_error_msg($exception->getMessage());
    }
  }
}
