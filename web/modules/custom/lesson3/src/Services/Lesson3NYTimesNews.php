<?php

namespace Drupal\lesson3\Services;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Component\Render\PlainTextOutput;
use Drupal\Component\Serialization\Json;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Url;
use Drupal\Core\Utility\Token;
use Drupal\field\Entity\FieldConfig;
use Drupal\taxonomy\Entity\Term;
use Drupal\webprofiler\Config\ConfigFactoryWrapper;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Profides service to fetch news data from https://api.nytimes.com.
 */
class Lesson3NYTimesNews {

  /**
   * NY Times service Api key value.
   *
   * @var string
   */
  private $nytimesApiKey;

  /**
   * NY Times service Api url value.
   *
   * @var string
   */
  private $nytimesApiUrl;

  /**
   * NY Times service Api key value.
   *
   * @var string
   */
  private $nytimesImageBaseUrl;

  /**
   * LoggerChannelFactoryInterface for logging.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $logger;

  /**
   * Drupal\Core\File\FileSystemInterface for saving files.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * Drupal\lesson3\Services\Lesson3KeywordsService definition.
   *
   * @var \Drupal\lesson3\Services\Lesson3KeywordsService
   */
  protected $keywordsService;

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * GuzzleHttp\Client definition.
   *
   * @var \GuzzleHttp\Client
   */
  protected $httpClient;

  /**
   * Drupal\Component\Datetime\TimeInterface definition.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $lesson3Time;

  /**
   * Drupal\webprofiler\Config\ConfigFactoryWrapper definition.
   *
   * @var \Drupal\webprofiler\Config\ConfigFactoryWrapper
   */
  protected $configFactory;

  /**
   * Drupal\Core\Utility\Token definition.
   *
   * @var \Drupal\Core\Utility\Token
   */
  protected $token;

  /**
   * Drupal\Core\Cache\CacheBackendInterface definition.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cacheBackend;

  const NEWS_CACHE_BIN = 'ny_times_news';
  const NEWS_CACHE_TAG = 'news_tag';

  /**
   * Constructs a new NYTimesNews object.
   */
  public function __construct(
    LoggerChannelFactoryInterface $logger,
    Lesson3KeywordsService $keywords_service,
    FileSystemInterface $file_system,
    EntityTypeManagerInterface $entity_type_manager,
    Client $http_client,
    TimeInterface $lesson3_time,
    ConfigFactoryWrapper $config_factory,
    Token $token,
    CacheBackendInterface $cache_backend,
  ) {
    $api_ny_times_config = $config_factory->get('lesson3.lesson3nytimesapi');
    $this->nytimesApiKey = $api_ny_times_config->get('nytimes_api_key');
    $this->nytimesApiUrl = $api_ny_times_config->get('nytimes_api_url');
    $this->nytimesImageBaseUrl = $api_ny_times_config->get('nytimes_image_base_url');
    $this->logger = $logger;
    $this->keywordsService = $keywords_service;
    $this->fileSystem = $file_system;
    $this->entityTypeManager = $entity_type_manager;
    $this->httpClient = $http_client;
    $this->lesson3Time = $lesson3_time;
    $this->configFactory = $config_factory;
    $this->token = $token;
    $this->cacheBackend = $cache_backend;
  }

  /**
   * Return data from api.nytimes.com.
   *
   * @param string $arg
   *   Argument from uri.
   * @param string $begin_date
   *   Start date for period to fetch news.
   * @param string $end_date
   *   End date for period to fetch news.
   * @param int $page
   *   Used in set page number.
   *
   * @return array
   *   Array which contains news data from https://api.nytimes.com.
   */
  public function getNews($arg = 'day', $begin_date = '', $end_date = '', $page = 1) {
    $news_data_cache = $this->cacheBackend->get(self::NEWS_CACHE_BIN);
    $news_data = $news_data_cache ? $news_data_cache->data : [];

    if (!$news_data) {
      $query = [
        'page' => $page,
        'sort' => 'newest',
        'api-key' => $this->nytimesApiKey,
      ];

      switch ($arg) {
        case 'day':
          $query['begin_date'] = $query['end_date'] = date('Ymd', $this->lesson3Time->getRequestTime());
          break;

        case 'week':
          $publish_date_week_ago = date('Ymd', strtotime('-1 week'));
          $query['begin_date'] = $publish_date_week_ago;
          $query['end_date'] = date('Ymd', $this->lesson3Time->getRequestTime());
          break;

        default:
          $query['begin_date'] = $begin_date;
          $query['end_date'] = $end_date;
          break;
      }
      $query_str = UrlHelper::buildQuery($query);
      $request_uri = $this->nytimesApiUrl . '?' . $query_str;

      try {
        $data = $this->httpClient
          ->get($request_uri)
          ->getBody();
        $content_arr = Json::decode($data->getContents());
        if (!empty($content_arr)) {
          $tags = [self::NEWS_CACHE_TAG];
          $expire = $this->lesson3Time->getRequestTime() + 24 * 3600;
          $data = $content_arr["response"];
          $this->cacheBackend->set(self::NEWS_CACHE_BIN, $data, $expire, $tags);

          // Return data with news items.
          return $data;
        }
      }
      catch (RequestException $exception) {
        $this->logger->get('lesson3')
          ->error('Failed to fetch news data due to error "%error"', [
            '%error' => $exception->getMessage(),
          ]);
        return FALSE;
      }
    }
    else {
      return $news_data;
    }

    return $news_data;
  }

  /**
   * Process data to use both in page and block.
   *
   * @param array $news_data
   *   Array of news data fetch from https://api.nytimes.com.
   *
   * @return array
   *   Rendered array of news data.
   */
  public function processNewsData(array $news_data) {
    $items = [];
    $render_array = [];
    foreach ($news_data as $news_item) {
      // Creating links from nytimes service data.
      $link = [
        '#type' => 'link',
        '#attributes' => [
          'target' => '_blank',
        ],
        '#title' => $news_item["headline"]["main"],
        '#url' => Url::fromUri($news_item["web_url"]),
      ];

      // Passing link and pub_date to items area.
      $items[] = [
        'pub_date' => date('Y-m-d H:i', strtotime($news_item["pub_date"])),
        'link' => $link,
      ];
    }

    // Passing items to nytimes_news_list defined in hook_theme.
    $render_array['news_list'] = [
      '#theme' => 'nytimes_news_list',
      '#title' => 'News',
      '#news_items' => $items,
    ];

    return $render_array;
  }

  /**
   * Create news entity.
   */
  public function createNewsEntity($data_item) {
    $news_data = [
      'nyt_id' => $data_item["_id"],
      'title' => $data_item["headline"]["main"],
      'publication_date' => strtotime($data_item["pub_date"]),
      'source' => $data_item["source"],
      'document_type' => $data_item["document_type"],
      'type_of_material' => $data_item["type_of_material"],
      'score' => $data_item["score"],
      'field_teaser' => [
        'value' => $data_item["snippet"],
      ],
      'field_web_url' => [
        'uri' => $data_item["web_url"],
        'title' => $data_item["headline"]["main"],
      ],
    ];

    // Check if taxonomy term exists for section_name.
    if (!empty($data_item["section_name"])) {
      $terms = taxonomy_term_load_multiple_by_name($data_item["section_name"]);
      if (empty($terms)) {
        $section_term = Term::create([
          'name' => $data_item["section_name"],
          'vid' => 'section_name',
        ]);
        $section_term->save();
      }
      else {
        $section_term = reset($terms);
      }
      $news_data['section_name'] = [
        'target_id' => $section_term->id(),
      ];
    }

    // Saving news images.
    $field_multimedia = [];
    foreach ($data_item["multimedia"] as $key => $image_data_item) {
      if (!empty($image_data_item["url"])) {
        $url = $this->nytimesImageBaseUrl . '/' . $image_data_item["url"];
        $data = file_get_contents($url);
        $file_name = basename($url);

        // Get field config to get settings File directory.
        $field_config = FieldConfig::loadByName('news_entity', 'news_entity',
          'field_multimedia');
        $settings = $field_config->getSettings();
        $destination = trim($settings['file_directory'], '/');
        $destination = PlainTextOutput::renderFromHtml($this->token->replace($destination));
        $file_directory = $settings['uri_scheme'] . '://' . $destination;

        $this->fileSystem->prepareDirectory($file_directory,
          FileSystemInterface::CREATE_DIRECTORY);
        $file = file_save_data($data, $file_directory . '/' . $file_name,
          FileSystemInterface::EXISTS_REPLACE);

        $field_multimedia[] = [
          'target_id' => $file->id(),
          'alt' => $data_item["headline"]["main"],
        ];
      }
    }
    if ($field_multimedia) {
      $news_data['field_multimedia'] = $field_multimedia;
    }

    // Check if entity exists, create new or update existing.
    $news_entity = \Drupal::entityQuery('news_entity')->condition(
      'nyt_id', $data_item["_id"]
    )->execute();

    if ($news_entity) {
      $id = reset($news_entity);
      $news_entity = $this->entityTypeManager
        ->getStorage('news_entity')->load($id);

      // Update entity.
      foreach ($news_data as $field_name => $field_value) {
        $news_entity->set($field_name, $field_value);
      }
      $news_entity->save();

      $message_process_name = 'Updated';
    }
    else {
      $news_entity = $this->entityTypeManager->getStorage('news_entity')
        ->create($news_data);
      $news_entity->save();
      $message_process_name = 'Created';
    }

    foreach ($data_item["keywords"] as $key => $keyword_item) {
      $keywords_data[$key] = [
        'entity_id' => $news_entity->id(),
        'entity_vid' => $news_entity->id(),
        'is_major' => $keyword_item["isMajor"] == 'N' ? 0 : 1,
        'rank' => $keyword_item["rank"],
        'name' => $keyword_item["name"],
        'value' => $keyword_item["value"],
      ];

      $key_merge = ['entity_id' => $news_entity->id()];
      $this->keywordsService->merge($key_merge, $keywords_data[$key], $keywords_data[$key]);
    }

    return [
      'process_name' => $message_process_name,
      'news_entity' => $news_entity,
    ];
  }

}
