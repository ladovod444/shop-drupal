<?php

namespace Drupal\lesson3;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the News entity entity.
 *
 * @see \Drupal\lesson3\Entity\NewsEntity.
 */
class NewsEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\lesson3\Entity\NewsEntityInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished news entity entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published news entity entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit news entity entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete news entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add news entity entities');
  }

}
