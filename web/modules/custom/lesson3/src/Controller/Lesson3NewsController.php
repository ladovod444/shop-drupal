<?php

namespace Drupal\lesson3\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Lesson3NewsController.
 *
 * Used to output page of news fetch from https://api.nytimes.com.
 */
class Lesson3NewsController extends ControllerBase {

  /**
   * Drupal\lesson3\NYTimesNews definition.
   *
   * @var \Drupal\lesson3\Services\Lesson3NYTimesNews
   */
  protected $lesson3NytimesNews;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->lesson3NytimesNews = $container->get('lesson3.nytimes_news');

    return $instance;
  }

  /**
   * Output a page of news items.
   *
   * @return array
   *   Return rendered array with news data and pagination.
   */
  public function display($arg) {
    $page = !empty($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] + 1 : 1;
    $build = [
      '#create_placeholder' => TRUE,
      '#lazy_builder' => [
        'lesson3.news_lazy_builder:renderNews', ['', '', $arg, $page],
      ],
    ];

    return $build;
  }

  /**
   * Return title for a page if it's a day or week news.
   *
   * @return string
   *   page title string
   */
  public function getTitle($arg) {
    return $this->t('News of the @arg', ['@arg' => $arg]);
  }

}
