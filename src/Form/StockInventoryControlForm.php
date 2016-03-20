<?php

namespace Drupal\commerce_stock\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class StockInventoryControlForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'stock_inventory_control_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#theme'] = array('commerce_stock_inventory_control_form');
    $form['#test_var'] = 'Looking for a way to render dropdown';

    $form['sku'] = [
      '#type' => 'textfield',
      '#autocomplete_route_name' => 'commerce_stock.sku_autocomplete',
      '#placeholder' => t('Scan or Type SKU number...'),
      '#required' => FALSE,
      '#title' => $this->t('SKU'),
    ];

    $locations = \Drupal::entityTypeManager()->getStorage('commerce_stock_location')->loadMultiple();

    $options = [];
    foreach ($locations as $lid => $location) {
      $options[$lid] = $location->get('name')->value;
    }

    $form['location'] = [
      '#type' => 'select',
      '#options' => $options,
      '#required' => TRUE,
      '#default_value' => $_SESSION['commerce_stock_movement_form_location_id'],
      '#title' => $this->t('Stock Location'),
    ];

    $form['description'] = [
      '#type' => 'textarea',
      '#default_value' => NUll,
      '#required' => FALSE,
      '#placeholder' => t('Please provide a log entry...'),
      '#title' => $this->t('Description'),
    ];

    $form['actions'] = array(
      '#type' => 'fieldset',
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
    );

    $form['actions']['sell'] = [
      '#type' => 'submit',
      '#value' => $this->t('Sell'),
    ];
    $form['actions']['return'] = [
      '#type' => 'submit',
      '#value' => $this->t('Return'),
    ];
    $form['actions']['fill'] = [
      '#type' => 'submit',
      '#value' => $this->t('Fill'),
    ];
    $form['actions']['move'] = [
      '#type' => 'submit',
      '#value' => $this->t('Move'),
    ];
    $form['actions']['delete'] = [
      '#type' => 'submit',
      '#value' => $this->t('Delete'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $op = $form_state->getValue('op');
    $des = $form_state->getValue('description');

  }

}