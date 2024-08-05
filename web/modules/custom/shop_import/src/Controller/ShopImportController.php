<?php

declare(strict_types=1);

namespace Drupal\shop_import\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Shop import routes.
 */
final class ShopImportController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function __invoke(): array {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

}
