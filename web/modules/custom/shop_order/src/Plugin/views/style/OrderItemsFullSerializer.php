<?php

namespace Drupal\shop_order\Plugin\views\style;

use Drupal\commerce_order\Entity\OrderItem;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Cache\CacheableDependencyInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\style\StylePluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Drupal\rest\Plugin\views\style\Serializer;

/**
 * The style plugin for serialized output formats.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "order_items_full_serializer",
 *   title = @Translation("Order items full serializer"),
 *   help = @Translation("Serializes views row data with additional order items."),
 *   display_types = {"data"}
 * )
 */
class OrderItemsFullSerializer extends Serializer {

  /**
   * {@inheritdoc}
   */
  protected $usesRowPlugin = TRUE;

  /**
   * {@inheritdoc}
   */
  protected $usesGrouping = FALSE;

  /**
   * The serializer which serializes the views result.
   *
   * @var \Symfony\Component\Serializer\Serializer
   */
  protected $serializer;

  /**
   * The available serialization formats.
   *
   * @var array
   */
  protected $formats = [];

  /**
   * The serialization format providers, keyed by format.
   *
   * @var string[]
   */
  protected $formatProviders;

  /**
   * {@inheritdoc}
   */
//  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
//    return new static(
//      $configuration,
//      $plugin_id,
//      $plugin_definition,
//      $container->get('serializer'),
//      $container->getParameter('serializer.formats'),
//      $container->getParameter('serializer.format_providers')
//    );
//  }

  /**
   * Constructs a Plugin object.
   */
//  public function __construct(array $configuration, $plugin_id, $plugin_definition, SerializerInterface $serializer, array $serializer_formats, array $serializer_format_providers) {
//    parent::__construct($configuration, $plugin_id, $plugin_definition);
//
//    $this->definition = $plugin_definition + $configuration;
//    $this->serializer = $serializer;
//    $this->formats = $serializer_formats;
//    $this->formatProviders = $serializer_format_providers;
//  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitOptionsForm(&$form, FormStateInterface $form_state) {
    parent::submitOptionsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    $rows = [];
    // If the Data Entity row plugin is used, this will be an array of entities
    // which will pass through Serializer to one of the registered Normalizers,
    // which will transform it to arrays/scalars. If the Data field row plugin
    // is used, $rows will not contain objects and will pass directly to the
    // Encoder.

    foreach ($this->view->result as $row_index => $row) {
      $this->view->row_index = $row_index;

      // Get order items.
      $order_items = $row->_entity->toArray()['order_items'];
      $order_items_full = [];
      foreach ($order_items as $order_item) {
        $order_item = OrderItem::load($order_item['target_id']);
        $order_items_full[] = [
          'oid' => $order_item->id(),
          'title' => $order_item->getTitle(),
          'quantity' => $order_item->getQuantity(),
          'price' => $order_item->getUnitPrice()->toArray(),
          'total_price' => $order_item->getTotalPrice()->toArray(),
        ];
      }
      $rows[$row_index] = $this->view->rowPlugin->render($row);
      $rows[$row_index]['order_items_full'] = $order_items_full;

    }
    unset($this->view->row_index);

    // Get the content type configured in the display or fallback to the
    // default.
    if ((empty($this->view->live_preview))) {
      $content_type = $this->displayHandler->getContentType();
    }
    else {
      $content_type = !empty($this->options['formats']) ? reset($this->options['formats']) : 'json';
    }
    return $this->serializer->serialize($rows, $content_type, ['views_style_plugin' => $this]);
  }

  /**
   * Gets a list of all available formats that can be requested.
   *
   * This will return the configured formats, or all formats if none have been
   * selected.
   *
   * @return array
   *   An array of formats.
   */
  public function getFormats() {
    return $this->options['formats'];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return Cache::PERMANENT;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    return ['request_format'];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function calculateDependencies() {
    $dependencies = parent::calculateDependencies();
    $formats = $this->getFormats();
    $providers = array_intersect_key($this->formatProviders, array_flip($formats));
    // The plugin always uses services from the serialization module.
    $providers[] = 'serialization';

    $dependencies += ['module' => []];
    $dependencies['module'] = array_merge($dependencies['module'], $providers);
    return $dependencies;
  }

  /**
   * Returns an array of format options.
   *
   * @return string[]
   *   An array of format options. Both key and value are the same.
   */
  protected function getFormatOptions() {
    $formats = array_keys($this->formatProviders);
    return array_combine($formats, $formats);
  }

}
