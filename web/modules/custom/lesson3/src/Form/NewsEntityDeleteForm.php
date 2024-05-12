<?php

namespace Drupal\lesson3\Form;

use Drupal\Core\Entity\ContentEntityDeleteForm;

/**
 * Provides a form for deleting News entity entities.
 *
 * @ingroup lesson3
 */
class NewsEntityDeleteForm extends ContentEntityDeleteForm {

  /**
   * {@inheritdoc}
   */
  protected function getDeletionMessage() {
    return $this->t('The @type %title has been deleted.', [
      '@type' => 'news entity',
      '%title' => $this->getEntity()->getTitle(),
    ]);
  }

}
