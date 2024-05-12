<?php

namespace Drupal\lesson3\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class Lesson3NYTimesAPIForm use to manage config nytimes_api_key.
 */
class Lesson3NYTimesAPIForm extends ConfigFormBase {

  const NYTIMES_API_KEY_DEFAULT = '8199df9d138747378e29648ad1478339';

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'lesson3.lesson3nytimesapi',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'lesson3_nytimes_api_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('lesson3.lesson3nytimesapi');

    $form['nytimes_api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('NYTimes Api key'),
      '#description' => $this->t('Store api key for NYTimes service'),
      '#default_value' => $config->get('nytimes_api_key'),
    ];

    $form['nytimes_api_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('NYTimes Api url'),
      '#description' => $this->t('Store url for NYTimes service'),
      '#default_value' => $config->get('nytimes_api_url'),
    ];

    $form['nytimes_image_base_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('NYTimes image base url'),
      '#description' => $this->t('Store image base url for NYTimes service'),
      '#default_value' => $config->get('nytimes_image_base_url'),
    ];

    $form['items_per_page'] = [
      '#type' => 'number',
      '#title' => $this->t('Items per page'),
      '#description' => $this->t('Store items per page'),
      '#default_value' => $config->get('items_per_page'),
    ];

    $form['pager_quantity'] = [
      '#type' => 'number',
      '#title' => $this->t('Number of links in pager'),
      '#description' => $this->t('Store number of links in pager'),
      '#default_value' => $config->get('pager_quantity'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('lesson3.lesson3nytimesapi')
      ->set('nytimes_api_key', $form_state->getValue('nytimes_api_key'))
      ->set('nytimes_api_url', $form_state->getValue('nytimes_api_url'))
      ->set('nytimes_image_base_url', $form_state->getValue('nytimes_image_base_url'))
      ->set('pager_quantity', $form_state->getValue('pager_quantity'))
      ->set('items_per_page', $form_state->getValue('items_per_page'))
      ->save();
  }

}
