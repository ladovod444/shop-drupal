<?php

namespace Drupal\lesson3\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form to output inputed values after submit.
 *
 * @see \Drupal\Core\Form\FormBase
 */
class Lesson3NewsForm extends FormBase {

  /**
   * Drupal\Component\Datetime\TimeInterface definition.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $lesson3Time;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'lesson3_news_form';
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->lesson3Time = $container->get('datetime.time');

    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['date'] = [
      '#type' => 'date',
      '#title' => $this->t('Date'),
      '#default_value' => date('Y.m.d', $this->lesson3Time->getRequestTime()),
    ];

    $form['range'] = [
      '#type' => 'range',
      '#title' => $this->t('Range'),
    ];

    $form['number'] = [
      '#type' => 'number',
      '#title' => $this->t('Number'),
    ];

    $options = [
      'world' => 'World',
      'russia' => 'Russia',
      'usa' => 'USA',
    ];
    $form['select'] = [
      '#type' => 'select',
      '#title' => $this->t('Select news type'),
      '#options' => $options,
    ];

    $form['pass'] = [
      '#type' => 'password',
      '#title' => $this->t('Password'),
      '#size' => 25,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Display result.
    foreach ($form_state->getValues() as $key => $value) {
      $this->messenger()->addMessage($key . ': ' . ($key === 'text_format' ? $value['value'] : $value));
    }
  }

}
