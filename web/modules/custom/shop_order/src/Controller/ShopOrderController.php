<?php

declare(strict_types=1);

namespace Drupal\shop_order\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for Shop order routes.
 */
final class ShopOrderController extends ControllerBase {

  /**
   * The controller constructor.
   */
  public function __construct(
    private readonly RouteMatchInterface $routeMatch,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('current_route_match'),
    );
  }

  /**
   * Builds the response.
   */
  public function __invoke(): array {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    $build['test'] = [
      '#theme' => 'shop_create_order',
      '#email_title_text' => 'Some text',
      '#email_body_content' => 'Some body text',
    ];

    return $build;
  }

}
