<?php

// declare(strict_types=1);.
namespace Drupal\shop_import;

use Drupal\commerce_price\Price;
use Drupal\commerce_product\Entity\Product;
use Drupal\commerce_product\Entity\ProductVariation;
use Drupal\Component\Render\PlainTextOutput;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Session\AccountProxy;
use Drupal\Core\Utility\Token;
use Drupal\field\Entity\FieldConfig;
use Drupal\file\FileRepository;
use GuzzleHttp\Client;

/**
 * @todo Add class description.
 */
final class ShopImport {

  /**
   * Constructs a ShopImport object.
   */
  public function __construct(
    private readonly EntityTypeManagerInterface $entityTypeManager,
    private ConfigFactoryInterface $configFactory,
    private Client $httpClient,
    private FileSystemInterface $fileSystem,
    private Token $token,
    private FileRepository $fileRepository,
    private AccountProxy $account,
  ) {}

  /**
   * @todo Add method description.
   */
  public function productsImport(): string|array {
    $api_config = $this->configFactory->get('shop_import.settings');

    $api_key = $api_config->get('api_key');
    $api_url = $api_config->get('api_url');

    $response = $this->httpClient->request('GET', $api_url, [
      'headers'        => [
        'Authorization' => $api_key,
      ],
    ]);

    $response_data = json_decode($response->getBody()->getContents(), TRUE);
    // var_dump($response_data['shop']);.
    $shop_data = $response_data['shop'];

    $test_count = 0;
    // Foreach ($shop_data as $shop_item) {
    //      // @todo add checking whether product exist
    //      $this->createProduct($shop_item, $api_key, $api_url);
    //      $test_count++;
    //      if ($test_count > 30) {
    //        break;
    //      }
    //    }.
    return $shop_data;
  }

  /**
   *
   */
  public function createProduct(array $data_item) {

    $query = $this->entityTypeManager->getStorage('commerce_product')->getQuery();
    $query->condition('status', 1)
      ->condition('type', 'default')
      ->condition('field_mainid', $data_item["mainId"]);
    $query->accessCheck(FALSE);
    $ids = $query->execute();

    $entities = \Drupal::entityTypeManager()->getStorage('commerce_product')->loadMultiple($ids);

    if (!count($entities)) {
      $product_data = [
        'mainId' => $data_item["mainId"],
        'title' => $data_item["displayName"],
        'displayDescription' => $data_item["displayDescription"],
        'offerId' => $data_item["offerId"],
        'price' => $data_item["price"],
        // 'publication_date' => strtotime($data_item["pub_date"]),
      ];

      // Image save example.
      $field_product_image = [];
      foreach ($data_item["displayAssets"] as $key => $image_data_item) {
        if (!empty($image_data_item["url"])) {
          // $url = $api_url . '/' . $image_data_item["url"];
          $url = $image_data_item["url"];
          $data = file_get_contents($url);
          $file_name = basename($url);

          // Get field config to get settings File directory.
          $field_config = FieldConfig::loadByName('commerce_product', 'default',
            'field_product_image');
          $settings = $field_config->getSettings();
          $destination = trim($settings['file_directory'], '/');
          $destination = PlainTextOutput::renderFromHtml($this->token->replace($destination));
          $file_directory = $settings['uri_scheme'] . '://' . $destination;

          $this->fileSystem->prepareDirectory($file_directory,
            FileSystemInterface::CREATE_DIRECTORY);
          // $file = file_save_data($data, $file_directory . '/' . $file_name,
          //          FileSystemInterface::EXISTS_REPLACE);
          $file = $this->fileRepository->writeData($data, $file_directory . '/' . $file_name,
            FileSystemInterface::EXISTS_REPLACE);

          // Return $save ? file_save_data($data, NodeExport::getFileUri($format), FileSystemInterface::EXISTS_REPLACE) : $data;
          // return $save ? \Drupal::service('file.repository')->writeData($data, NodeExport::getFileUri($format), FileSystemInterface::EXISTS_REPLACE) : $data;
          //        $field_product_image[] = [
          //          'target_id' => $file->id(),
          //          //'alt' => $data_item["headline"]["main"],
          //        ];.
          $field_product_image = [
            'target_id' => $file->id(),
            // 'alt' => $data_item["headline"]["main"],
          ];
        }
      }
      if ($field_product_image) {
        $product_data['field_product_image'] = $field_product_image;
      }

      $this->createNewProduct($product_data);
    }
  }

  /**
   *
   */
  private function createNewProduct($product_data) {

    $sku = 'product-sku' . $product_data["offerId"];
    $query = \Drupal::entityQuery('commerce_product_variation');
    $query->condition('sku', $sku);
    $query->accessCheck(TRUE);
    // $query->condition('status', 1);
    $product_ids = $query->execute();
    // Check product_ids.
    if (!count($product_ids)) {
      $variation_large = ProductVariation::create([
        'type' => 'default',
        'sku' => 'product-sku' . $product_data["offerId"],
        'price' => new Price($product_data["price"]["regularPrice"], 'USD'),
        // 'attribute_color' => $blue,
        // 'attribute_size' => $large,
      ]);
      $variation_large->save();

      $variations = [
        $variation_large,
      ];

      $store = 1;

      // // Save imgae.
      //    $fid = $form_state->getValue(['file', 0]);
      //    if (!empty($fid)) {
      //      $file = File::load($fid);
      //      $file->setPermanent();
      //      $file->save();
      //    }
      // @todo Find SP
      $product = Product::create([
        'uid' => $this->account->id(),
        'type' => 'default',
        'title' => $product_data['title'],
        'stores' => [$store],
        'field_mainid' => [
          'value' => $product_data['mainId'],
        ],

        // Attach variations to product.
        'variations' => $variations,
        // 'field_category' => [
        //        'target_id' => $form_state->getValue('category'),
        //      ],
        // Attach image to product.
        'field_product_image' => $product_data['field_product_image']['target_id'] ? [
          // Set file_id.
          'target_id' => $product_data['field_product_image']['target_id'],

        ] : [],
        'body' => [
          'value' => $product_data["displayDescription"],
          'format' => 'full_html',
        ],

      ]);
      $product->save();
    }
    else {
      // @todo UPDATE.
    }
  }

}
