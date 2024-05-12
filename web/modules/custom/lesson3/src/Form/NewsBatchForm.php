<?php

namespace Drupal\lesson3\Form;

use Drupal\Core\Batch\BatchBuilder;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\lesson3\Services\Lesson3NYTimesNews;

/**
 * Class NewsBatchForm.
 *
 * Define form for batch creating news entities.
 */
class NewsBatchForm extends FormBase {

  /**
   * Drupal\lesson3\NYTimesNews definition.
   *
   * @var \Drupal\lesson3\Services\Lesson3NYTimesNews
   */
  protected $lesson3NytimesNews;

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
   * BatchForm constructor.
   */
  public function __construct() {
    $this->batchBuilder = new BatchBuilder();
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    $instance = parent::create($container);
    $instance->lesson3NytimesNews = $container->get('lesson3.nytimes_news');
    $instance->cacheTagsInvalidator = $container->get('cache_tags.invalidator');

    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'news_batch_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['execute_batch'] = [
      '#type' => 'submit',
      '#value' => $this->t('Execute batch'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $news_data = $this->lesson3NytimesNews->getNews('day');
    $this->batchBuilder
      ->setTitle($this->t('Processing an News Entities of @num items', [
        '@num' => count($news_data['docs']),
      ]))
      ->setProgressMessage($this->t('Completed @current of @total.'))
      ->setFinishCallback([$this, 'batchFinished'])
      ->setInitMessage(t('Batch starting'));
    // Prepare data for batch.
    foreach ($news_data['docs'] as $key => $news_item) {
      $this->batchBuilder->addOperation([$this, 'batchCreateNewsEntity'], [
        $news_item,
      ]);
    }
    batch_set($this->batchBuilder
      ->toArray());
  }

  /**
   * Processor for item fo batch operations.
   */
  public function batchCreateNewsEntity($data_item, &$context) {
    $result = $this->lesson3NytimesNews->createNewsEntity($data_item);
    $this->messenger()->addMessage(t('@process_name News entity "@id"', [
      '@process_name' => $result['process_name'],
      '@id' => $result['news_entity']->id(),
    ]));
    $context['results'][1][] = $result['news_entity']->id();
    $context['results']['processed'] += 1;
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

    $this->cacheTagsInvalidator->invalidateTags([Lesson3NYTimesNews::NEWS_CACHE_TAG]);
  }

}
