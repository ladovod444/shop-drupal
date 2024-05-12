<?php

namespace Drupal\lesson3\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class Lesson7ConfigIForm use to manage date used in lesson7.
 */
class Lesson7ConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'lesson3.lesson7config',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'lesson7_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('lesson3.lesson7config');

    $form['response_redirect_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Response redirect url'),
      '#description' => $this->t('Store response redirect url value'),
      '#default_value' => $config->get('response_redirect_url'),
    ];

    $form['response_max_age'] = [
      '#type' => 'number',
      '#title' => $this->t('Response max age'),
      '#description' => $this->t('Store response max age'),
      '#default_value' => $config->get('response_max_age'),
    ];

    $form['response_entities_count'] = [
      '#type' => 'number',
      '#title' => $this->t('Response entities count'),
      '#description' => $this->t('Store response entities count'),
      '#default_value' => $config->get('response_entities_count'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('lesson3.lesson7config')
      ->set('response_redirect_url', $form_state->getValue('response_redirect_url'))
      ->set('response_max_age', $form_state->getValue('response_max_age'))
      ->set('response_entities_count', $form_state->getValue('response_entities_count'))
      ->save();
  }

}
