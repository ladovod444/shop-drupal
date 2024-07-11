<?php

declare(strict_types=1);

namespace Drupal\shop_order\Plugin\rest\resource;

use Drupal\commerce_cart\CartProvider;
use Drupal\commerce_order\Entity\Order;
use Drupal\commerce_order\Entity\OrderItem;
use Drupal\commerce_price\Price;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Represents ShopOrderResource records as resources.
 *
 * @RestResource (
 *   id = "shop_order_shoporderresource",
 *   label = @Translation("ShopOrderResource"),
 *   uri_paths = {
 *     "canonical" = "/api/v2/shop-order-resource/{id}",
 *     "create" = "/api/v2/shop-order-resource"
 *   }
 * )
 *
 * @DCG
 * The plugin exposes key-value records as REST resources. In order to enable it
 * import the resource configuration into active configuration storage. An
 * example of such configuration can be located in the following file:
 * core/modules/rest/config/optional/rest.resource.entity.node.yml.
 * Alternatively, you can enable it through admin interface provider by REST UI
 * module.
 * @see https://www.drupal.org/project/restui
 *
 * @DCG
 * Notice that this plugin does not provide any validation for the data.
 * Consider creating custom normalizer to validate and normalize the incoming
 * data. It can be enabled in the plugin definition as follows.
 * @code
 *   serialization_class = "Drupal\foo\MyDataStructure",
 * @endcode
 *
 * @DCG
 * For entities, it is recommended to use REST resource plugin provided by
 * Drupal core.
 * @see \Drupal\rest\Plugin\rest\resource\EntityResource
 */
final class ShopOrderResource extends ResourceBase {

  use MessengerTrait;

  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * The cart manager for test cart operations.
   *
   * @var \Drupal\commerce_cart\CartManager
   */
  protected $cartManager;

  /**
   * The cart provider.
   *
   * @var \Drupal\commerce_cart\CartProvider
   */
  protected CartProvider $cartProvider;

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * A current user instance.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $request;

  /**
   * A current user instance.
   *
   * @var \Drupal\shop_order\ShopOrderMail
   */
  protected $shopOrderMail;

  const ORDER_LABEL = 'Order successfully created';

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition);
    $instance->logger = $container->get('logger.factory')->get('map_delivery');
    $instance->currentUser = $container->get('current_user');
    $instance->cartManager = $container->get('commerce_cart.cart_manager');
    $instance->cartProvider = $container->get('commerce_cart.cart_provider');
    $instance->entityTypeManager = $container->get('entity_type.manager');
    $instance->request = $container->get('request_stack');
    $instance->shopOrderMail = $container->get('shop_order.mail');
    return $instance;
  }

  /**
   * @param $order
   * @param $values
   * @param $uid
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   *
   * Creates Billing profile and assign it to order
   */
  protected function setOrderBillingProfile($order, $country_code, $city, $province, $street, $uid) {
    $profile = $order->getBillingProfile();

    if (empty($profile)) {
      $profile = Profile::create([
        'type' => 'customer',
        'uid' => $uid,
      ]);
      $profile->save();
    }

    // CA.
    $profile->address->country_code = $country_code;
    $profile->address->locality = $city;
    $profile->address->administrative_area = $province;
    // $profile->address->postal_code = $values["data"]["result"]["token"]["card"]["address_zip"];
    $profile->address->address_line1 = $street;
    // $profile->address->address_line2 = $values["data"]["result"]["token"]["card"]["address_line2"];
    $profile->save();

    $order->setBillingProfile($profile);
    $order->save();
  }

  /**
   * Responds to GET requests.
   *
   * @param string $payload
   *
   * @return \Drupal\rest\ModifiedResourceResponse
   *   The HTTP response object.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function post($data) {
    $storeId = 1;
    // $host = $this->request->getCurrentRequest()->getSchemeAndHttpHost();
    //    // Collecting data.
    //    $query = $this->request->getCurrentRequest()->query;
    // $generate_login_info = $query->get('generate_login_info');
    //    $location_to = $query->get('location_to');
    //    $country_code = $query->get('country_code');
    //    $province = $query->get('province');
    //    $city = $query->get('city');
    //    $street = $query->get('street');
    //    $client_name = $query->get('client_name');
    //    $contact = $query->get('contact');
    // $email = $query->get('email'); //
    $uid = $data['user_uid'];

    // If user is anonymous and need to generate login info, then create new user.
    if (!$uid) {
      // Check if user exists by email.
      $mail = $data['mail'];
      $ids = \Drupal::entityQuery('user')
        ->condition('mail', $mail)
        ->accessCheck(FALSE)
        ->execute();

      if (empty($ids)) {
        //$name = $client_name;
        $user = User::create();
        //$pass = map_custom_generate_pass();
        $pass = 'test'; // TODO replace with OTL Link or complicated password
        $user->setPassword($pass);
        $user->enforceIsNew();
        $user->setEmail($mail);

        // Name as email.
        $name = $mail;
        $user->setUsername($name); //This username must be unique and accept only a-Z,0-9, - _ @ .

        $user->activate();
        $user->save();
        $uid = $user->id();

        // TODO Send mail with ONT Link ???
      }
      else {
        $uid = reset($ids);
      }
    }

    // $order = $query->get('order');
    // $params = Json::decode($request->getContent());
    // @todo check what was received from client
    //   return new ModifiedResourceResponse(
    //      [
    //        'order' => $data,
    //        'uid' => $uid,
    //      ],
    //      200);
    /*

    // send email after user creation
    //$label = 'Order delivery and account creation';
    $label = 'Account creation';
    $body = [
    'header' => [
    '#markup' => '<p>Your Account at <a href="' . $host . '">' . $host . '</a></p>'
    ],
    'info' => [
    '#markup' => '<p>Copy and save login information</p>'
    ],
    'login_data' => [
    '#markup' => "<p>Username: $name<br> password: $pass</p>",
    ],
    'footer' => [
    '#markup' => '<p></p>'
    ],
    ];
    map_custom_send_mail($label, $body, $email, $name, $pass);
    //}
    }
     */

    // Getting data from cart.
    $cart_manager = $this->cartManager;
    $cart_provider = $this->cartProvider;

    // $current_user = $this->currentUser;
    $store = $this->entityTypeManager
      ->getStorage('commerce_store')
      ->load($storeId);

    $cart = $cart_provider->createCart('default', $store);

    // Set order to order "state".
    $cart->cart = FALSE;
    // $cart->state = 'canceled';
    // $cart->state = 'processing';
    $cart->state = 'draft';
    $cart->uid = $uid;
    $cart->save();

    // Create Billing profile.
    // $this->setOrderBillingProfile($cart, $country_code, $city, $province, $street, $uid);.
    // @todo .............
    //
    //      // You must to implement the logic of your REST Resource here.
    //      // Use current user after pass authentication to validate access.
    //      if (!$this->currentUser->hasPermission('access content')) {
    //          throw new AccessDeniedHttpException();
    //      }
    // Add message with link to track order.
    $cart_id = $cart->id();
    // ИЛИ добавить слой темизации для вывода нужного контента в письме

    $user = User::load($uid);
    $mail = $user->getEmail();

    // $rendered_message = Markup::create($message);
    foreach ($data['order'] as $data_order_item) {
      $order_item = OrderItem::create([
        'type' => 'default',
        'purchased_entity' => $data_order_item['variation_id'],
        'quantity' => $data_order_item['quantity'],
        // Omit these lines to preserve original product price.
        'unit_price' => new Price($data_order_item['price'], 'USD'),
        'overridden_unit_price' => TRUE,
      ]);
      $order_item->save();
      $cart->addItem($order_item);
    }

    $cart->field_state = 'new';
    $cart->save();
    $this->shopOrderMail->send(self::ORDER_LABEL, $cart_id, $mail, $data['order'], $cart->getTotalPrice()->toArray(), '', '');

    return new ModifiedResourceResponse([
        'order_id' => $cart->id(),
        // 'device_id' => $field_courier_entity_dev_id,
        'uuid' => $cart->uuid(),
        // 'message' => $message
      ], 200);
  }

  /**
   * Responds to GET requests.
   *
   * @param string $payload
   *
   * @return \Drupal\rest\ModifiedResourceResponse
   *   The HTTP response object.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function patch($data) {

    $order_id = $data['order_id'];
    $order = Order::load($order_id);
    $order->field_state = 'cancelled';
    $order->save();

    return new ModifiedResourceResponse([
      'data' => $data,
      // 'message' => $message
    ], 200);
  }

}
