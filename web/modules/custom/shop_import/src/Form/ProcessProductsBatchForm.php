<?php

namespace Drupal\shop_import\Form;

use Drupal\Core\Batch\BatchBuilder;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ProcessProductsBatchForm.
 *
 * Define form for batch processing product entities.
 */
class ProcessProductsBatchForm extends FormBase {

  const ITEMS_TO_PROCESS = 10;

  /**
   * Batch Builder.
   *
   * @var \Drupal\Core\Batch\BatchBuilder
   */
  protected $batchBuilder;

  /**
   * Drupal\Core\Cache\CacheTagsInvalidator definition.
   *
   * @var \Drupal\Core\Cache\CacheTagsInvalidator
   */
  protected $cacheTagsInvalidator;

  /**
   * Drupal\Core\Entity\EntityTypeManager definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  private ?EntityTypeManagerInterface $entityTypeManager = NULL;

  /**
   * Drupal\Core\Database\Connection definition.
   *
   * @var \Drupal\Core\Database\Connection
   */
  private ?object $database;

  private ?\Drupal\Core\Entity\Query\QueryInterface $query = NULL;

  /**
   * BatchForm constructor.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, Connection $database) {
    $this->entityTypeManager = $entity_type_manager;
    $this->database = $database;
    $this->batchBuilder = new BatchBuilder();
    // $this->query = $this->entityTypeManager->getStorage('commerce_product')->getQuery();
  }

  /**;
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    //    $instance = parent::create($container);
    //    $instance->entityTypeManager = $container->get('entity_type.manager');
    //    $instance->database = $container->get('database');

    return new static(
      $container->get('entity_type.manager'),
      $container->get('database')
    );
    //return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'process_products_batch_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['items_to_process'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Number of items to be process on one step'),
      '#required' => TRUE,
      '#default_value' => self::ITEMS_TO_PROCESS,
    ];

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['run'] = [
      '#type' => 'submit',
      '#value' => $this->t('Run batch'),
      '#button_type' => 'primary',
    ];

    //$products_data = $this->getUniqueProductsMainIds();
    //    $p = 1;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $products_data = $this->getUniqueProductsMainIds();
    //$products_data = [1,2,3,4,5,6,7,8,9,10];
    $this->batchBuilder
      ->setTitle($this->t('Processing Product Entities of @num items', [
        '@num' => count($products_data),
      ]))
      ->setProgressMessage($this->t('Completed @current of @total.'))
      ->setFinishCallback([$this, 'batchFinished'])
      ->setInitMessage(t('Batch starting'));

    // Prepare data for batch.
    //foreach ($products_data as $product_data) {
    for ($i = self::ITEMS_TO_PROCESS; $i < count($products_data); $i += self::ITEMS_TO_PROCESS) {
      $prepared_data = array_slice($products_data, $i);
      $this->batchBuilder->addOperation([
        $this,
        'batchProcessProductEntities',
      ], [
        $prepared_data,
      ]);
    }
    batch_set($this->batchBuilder
      ->toArray());
  }

  /**
   * Processor for N items fo batch operations.
   */
  function batchProcessProductEntities(array $product_data, &$context) {
    foreach ($product_data as $product_datum) {
      $this->batchProcessProductEntity($product_datum);
    }
    $this->messenger()->addMessage(t('@process_name Product entity "@id"', [
      '@process_name' => $product_data[0] . ' = ' . $product_data[count($product_data) - 1],
      '@id' => $product_data[0],
    ]));
    $context['results'][1][] = $product_data;
    $context['results']['processed'] += 1;
  }

  /**
   * Processor for item fo batch operations.
   */
  public function batchProcessProductEntity($data_item) {
    $result = $this->processProductEntity($data_item);
  }

  /**
   * Finished callback for batch.
   */
  public function batchFinished($success, $results, $operations) {
    $message = $this->t('Number of entities processed by batch: @count', [
      '@count' => $results['processed'],
    ]);
    $this->messenger()
      ->addStatus($message);

    $this->logger('news')
      ->notice('Number of news entities processed by batch: @count', [
        '@count' => $results['processed'],
      ]);
    //$this->cacheTagsInvalidator->invalidateTags([Lesson3NYTimesNews::NEWS_CACHE_TAG]);
  }

  /**
   * Processor for one product item.
   */
  private function processProductEntity($data) {
    //$query = $this->entityTypeManager->getStorage('commerce_product')->getQuery();
    $query = \Drupal::entityTypeManager()
      ->getStorage('commerce_product')
      ->getQuery();
    $query->condition('status', 1)
      ->condition('type', 'default')
      ->condition('field_mainid', $data);
    $query->accessCheck(FALSE);
    $ids = $query->execute();

    if (count($ids)) {
      $entities = \Drupal::entityTypeManager()
        ->getStorage('commerce_product')
        ->loadMultiple($ids);

      $count = 0;
      foreach ($entities as $key => $entity) {
        $count++;
        if ($count > 1) {
          $entity->delete();
        }
      }
    }
    return $data;
  }

  /**
   * Get Unique Products mainid fields.
   */
  private function getUniqueProductsMainIds() {
    $select = $this->database->select('commerce_product__field_mainid', 'fm');
    $select->fields('fm', ['field_mainid_value']);
    //$select->range(0,190);
    // 2481
    return array_unique($select->execute()->fetchCol());
  }

}
