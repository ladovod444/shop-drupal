<?php

namespace Drupal\lesson3\Services;

use Drupal\Core\Database\Connection;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;

/**
 * Class provides service to manage data for lesson3_keywords table.
 *
 * @ingroup lesson3
 */
class Lesson3KeywordsService {

  /**
   * Database service.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $dbConnection;

  /**
   * LoggerChannelFactoryInterface for logging.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $logger;
  const TABLE_NAME = 'lesson3_keywords';

  /**
   * Constructs a new DatabaseManager object.
   */
  public function __construct(Connection $dbConnection, LoggerChannelFactoryInterface $logger) {
    $this->dbConnection = $dbConnection;
    $this->logger = $logger;
  }

  /**
   * Insert operation.
   */
  public function add($fields, $values) {
    $query = $this->dbConnection->insert(self::TABLE_NAME);
    $query->fields($fields);
    foreach ($values as $record) {
      $query->values($record);
    }

    try {
      $return_value = $query->execute();
    }
    catch (\Exception $e) {
      $this->logger->get('lesson3')->error('Adding failed. Error = %error', [
        '%error' => $e->getMessage(),
      ]);
    }
    return $return_value ?? NULL;
  }

  /**
   * Select operation.
   */
  public function select($fields, $conditions = []) {
    $query = $this->dbConnection->select(self::TABLE_NAME, 'ls');
    $query->fields('ls', $fields);

    if ($conditions) {
      foreach ($conditions as $condition) {
        $query->condition(...$condition);
      }
    }
    return $query->execute()->fetchAll();
  }

  /**
   * Update operation.
   */
  public function update($fields, $conditions) {
    $query = $this->dbConnection->update(self::TABLE_NAME);
    $query->fields($fields);
    foreach ($conditions as $condition) {
      $query->condition(...$condition);
    }
    try {
      $query->execute();
    }
    catch (\Exception $e) {
      $this->logger->get('lesson3')->error('Update failed. Error = %error', [
        '%error' => $e->getMessage(),
      ]
      );
    }
    return $count ?? 0;
  }

  /**
   * Merge operation.
   */
  public function merge($key, $fields, $update_fields, $expression = []) {
    $query = $this->dbConnection->merge(self::TABLE_NAME);
    $query->key($key);

    if (!$update_fields) {
      $query->fields($fields);
      if ($expression) {
        $query->expression(...$expression);
      }
    }
    else {
      $query->insertFields($fields);
      $query->updateFields($update_fields);
    }

    try {
      $return_value = $query->execute();
    }
    catch (\Exception $e) {
      $this->logger->get('lesson3')->error('Merge failed. Error = %error', [
        '%error' => $e->getMessage(),
      ]);
    }
    return $return_value ?? NULL;
  }

  /**
   * Delete record from table.
   */
  public function delete($condition) {
    $query = $this->dbConnection->delete(self::TABLE_NAME);
    $query->condition(...$condition);
    $query->execute();
  }

}
