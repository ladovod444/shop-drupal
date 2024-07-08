<?php

namespace Drupal\lesson3\Services;

use Drupal\Core\Pager\PagerManager;
use Drupal\webprofiler\Config\ConfigFactoryWrapper;

/**
 * Class Lesson3NewsLazyBuilder.
 *
 *   Provides lazy builder news data.
 */
class Lesson3NewsLazyBuilder {

  /**
   * Drupal\lesson3\Services\Lesson3NYTimesNews definition.
   *
   * @var \Drupal\lesson3\Services\Lesson3NYTimesNews
   */
  protected $lesson3NytimesNews;

  /**
   * Drupal\Core\Pager\PagerManager definition.
   *
   * @var \Drupal\Core\Pager\PagerManager
   */
  protected $pager;

  /**
   * Drupal\webprofiler\Config\ConfigFactoryWrapper definition.
   *
   * @var \Drupal\webprofiler\Config\ConfigFactoryWrapper
   */
  protected $configFactory;

  /**
   * Constructs a new Lesson3NewsLazyBuilder object.
   */
  public function __construct(
    Lesson3NYTimesNews $lesson3_nytimes_news,
    PagerManager $pager,
    ConfigFactoryWrapper $config_factory,
  ) {
    $this->lesson3NytimesNews = $lesson3_nytimes_news;
    $this->pager = $pager;
    $this->configFactory = $config_factory;
  }

  /**
   * Get news items rendered array.
   */
  public function renderNews($news_begin_date, $news_end_date, $day = '', $page = 1) {
    $build = [];
    $news_data_service = $this->lesson3NytimesNews;
    // Request data from nytimes service.
    $news_data = $news_data_service->getNews($day, $news_begin_date, $news_end_date, $page);

    $build['content'] = $this->lesson3NytimesNews->processNewsData($news_data['docs']);
    if ($day) {
      $items_per_page = $this->configFactory->get('lesson3.lesson3nytimesapi')->get('items_per_page');
      $total_count = $news_data['meta']['hits'] - $items_per_page;
      $this->pager->createPager($total_count, $items_per_page);
      $build['pager'] = [
        '#type' => 'pager',
        '#quantity' => $this->configFactory->get('lesson3.lesson3nytimesapi')->get('pager_quantity'),
      ];
    }
    return $build;
  }

}
