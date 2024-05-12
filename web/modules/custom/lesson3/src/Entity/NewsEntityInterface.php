<?php

namespace Drupal\lesson3\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;

/**
 * Provides an interface for defining News entity entities.
 *
 * @ingroup lesson3
 */
interface NewsEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the News entity title.
   *
   * @return string
   *   Name of the News entity.
   */
  public function getTitle();

  /**
   * Sets the News entity title.
   *
   * @param string $title
   *   The News entity title.
   *
   * @return \Drupal\lesson3\Entity\NewsEntityInterface
   *   The called News entity entity.
   */
  public function setTitle($title);

  /**
   * Gets the News entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the News entity.
   */
  public function getCreatedTime();

  /**
   * Sets the News entity creation timestamp.
   *
   * @param int $timestamp
   *   The News entity creation timestamp.
   *
   * @return \Drupal\lesson3\Entity\NewsEntityInterface
   *   The called News entity entity.
   */
  public function setCreatedTime($timestamp);

}
