<?php

namespace Drupal\lesson3\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a block to output news.
 *
 * @Block(
 *  id = "lesson3_news_block",
 *  admin_label = @Translation("Lesson3 News block"),
 * )
 */
class Lesson3NewsBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\lesson3\NYTimesNews definition.
   *
   * @var \Drupal\lesson3\Services\Lesson3NYTimesNews
   */
  protected $lesson3NytimesNews;

  /**
   * Drupal\Component\Datetime\TimeInterface definition.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $lesson3Time;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = new static($configuration, $plugin_id, $plugin_definition);
    $instance->lesson3Time = $container->get('datetime.time');
    $instance->lesson3NytimesNews = $container->get('lesson3.nytimes_news');

    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $current_date = date('Y-m-d', $this->lesson3Time->getRequestTime());
    $form['news_begin_date'] = [
      '#type' => 'date',
      '#title' => $this->t('News begin date'),
      '#description' => $this->t('Select start date for news period'),
      '#default_value' => !empty($this->configuration['news_begin_date']) ? $this->configuration['news_begin_date'] : $current_date,
    ];
    $form['news_end_date'] = [
      '#type' => 'date',
      '#title' => $this->t('News end date'),
      '#description' => $this->t('Select end date for news period'),
      '#default_value' => !empty($this->configuration['news_end_date']) ? $this->configuration['news_end_date'] : $current_date,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['news_begin_date'] = $form_state->getValue('news_begin_date');
    $this->configuration['news_end_date'] = $form_state->getValue('news_end_date');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build['content'] = [
      '#create_placeholder' => TRUE,
      '#lazy_builder' => [
        'lesson3.news_lazy_builder:renderNews', [
          $this->configuration['news_begin_date'],
          $this->configuration['news_end_date'],
          '',
        ],
      ],
    ];

    return $build;
  }

}
