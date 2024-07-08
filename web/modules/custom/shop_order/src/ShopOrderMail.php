<?php

declare(strict_types=1);

namespace Drupal\shop_order;

use Drupal\commerce_order\Entity\OrderItem;
use Drupal\commerce_price\Price;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Symfony\Contracts\Translation\TranslatorTrait;

/**
 * @todo Add class description.
 */
final class ShopOrderMail {

  use StringTranslationTrait;

  /**
   * Constructs a ShopOrderMail object.
   */
  public function __construct(
    private readonly LoggerChannelFactoryInterface $loggerFactory,
    private readonly MailManagerInterface          $pluginManagerMail,
  ) {
  }

  /**
   * @todo Add method description.
   */
  public function send($label, $order_id, $mail, $data_order, $total_price, $name, $pass) {
    $module = 'shop_order';
    // Replace with Your key.
    $key = 'order_created';
    // $to = \Drupal::currentUser()->getEmail();
    $to = $mail;
    $message = $this->prepareMessage($data_order, $total_price, $order_id);

    $params['message'] = $message;
    $params['title'] = $label;
    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $send = TRUE;

    // @todo temp $mail
    $to = 'ladovod@gmail.com';

    $result = $this->pluginManagerMail->mail($module, $key, $to, $langcode, $params, NULL, $send);
    //$result = $this->pluginManagerMail->mail($module, $key, $to, $langcode);
    if ($result['result'] != TRUE) {
      $message = t('There was a problem sending your email notification to @email.', ['@email' => $to]);
      // drupal_set_message($message, 'error');.
      //\Drupal::messenger()->addError('There was a problem sending your email ' . $mail);
      $this->loggerFactory->get('mail-log')->error($message);
      return;
    }

    $message = t('An email notification has been sent to @email ', ['@email' => $to]);
    //\Drupal::messenger()->addMessage("Login info: username: $name, password: $pass");
    //\Drupal::messenger()->addMessage('An email with pass was sent to ' . $mail);
    $this->loggerFactory->get('shop_order')->notice($message);
  }

  private function prepareMessage($data_order, $total_price, $order_id) {
    $message_data = [];
    //$rows = [];
    $items = [];
    $items[] = 'Name Price Quantity Total price';
    foreach ($data_order as $order_item) {
      $rows[] = [
        $order_item['displayName'],
        $order_item['price'],
        $order_item['quantity'],
        $order_item['price'] * $order_item['quantity'],
      ];

//      $items[] =
//        $order_item['displayName'] . ' ' .
//        $order_item['price'] . ' ' .
//        $order_item['quantity'] . ' ' .
//        $order_item['price'] * $order_item['quantity'];
    }

    $message_data['table'] = [
      '#type' => 'table',
      '#caption' => $this->t('Order details.'),
      '#header' => [
        $this->t('Name'),
        $this->t('Price'),
        $this->t('Quantity'),
        $this->t('Total cost'),

      ],
      '#rows' => $rows,
    ];

//    $message_data['order_list'] = [
//      '#theme' => 'item_list',
//      '#title' => t('Order details.'),
//      //'#list_type' => 'ol',
//      '#items' => $items,
//      //'#attributes' => ['class' => ['my-item-list']],
//    ];
    $message_data['total'] = [
      '#prefix' => '<div>',
      '#markup' => $this->t('Total sum: @number @currency_code', [
        '@number' => $total_price['number'],
        '@currency_code' => $total_price['currency_code'],
      ]),
      '#suffix' => '</div>',
    ];

    $message_data['footer'] = [
      '#type' => 'link',
      '#title' => 'Track your order',
      '#url' => Url::fromUserInput('/orders/track/' . $order_id),
      '#attributes' => [
        'target' => '_blank',
        'title' => 'Track your order',
      ],
    ];
    return $message_data;
  }

}
