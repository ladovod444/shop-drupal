<?php

namespace Drupal\lesson3\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class Lesson7ResponsesController.
 *
 *   Controller to provide some kinds of responses.
 */
class Lesson7ResponsesController extends ControllerBase {

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->entityTypeManager = $container->get('entity_type.manager');

    return $instance;
  }

  /**
   * Response Redirect.
   *
   * Return TrustedRedirectResponse response.
   */
  public function responseRedirect() {
    $redirect_url = $this->config('lesson3.lesson7config')->get('response_redirect_url');
    return new TrustedRedirectResponse($redirect_url);
  }

  /**
   * Response Json.
   *
   * Return news items data in JsonResponse response.
   */
  public function responseJson() {
    $response = new JsonResponse();
    $items = [];
    $news_items = \Drupal::entityQuery('news_entity')
      ->sort('publication_date', 'DESC')
      ->range(0, $this->config('lesson3.lesson7config')->get('response_entities_count'))
      ->execute();

    $news_items = $this->entityTypeManager->getStorage('news_entity')
      ->loadMultiple($news_items);
    foreach ($news_items as $news_item) {
      $items[] = [
        'title' => $news_item->getTitle(),
        'url' => $news_item->get('field_web_url')->uri,
      ];
    }
    $response->setCache(['max_age' => $this->config('lesson3.lesson7config')->get('response_max_age')]);
    $response->setData($items);

    return $response;
  }

}
