<?php

namespace Drupal\commerce_stock\Form;

use Drupal\commerce_stock\Entity\Stock;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\commerce_product\Entity\ProductVariation;

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

    $form['sku'] = [
      '#type' => 'textfield',
      '#autocomplete_route_name' => 'commerce_stock.sku_autocomplete',
      '#placeholder' => t('Scan or Type SKU number...'),
      '#required' => FALSE,
      '#title' => $this->t('SKU'),
    ];

    $locations = \Drupal::entityTypeManager()->getStorage('commerce_stock_location')->loadMultiple();

    $options = [];
    $user = \Drupal::currentUser();
    foreach ($locations as $lid => $location) {
      if ($user->hasPermission('administer stock entity') || $user->hasPermission('edit stock entity at any location')) {
        $options[$lid] = $location->get('name')->value;
      } else if ($user->hasPermission('edit stock entity at own location')) {
        $uids = $location->get('uid');
        foreach ($uids as $manager) {
          if ($manager->target_id == $user->id()) {
            $options[$lid] = $location->get('name')->value;
          }
        }
      }
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
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $skus = $form_state->getUserInput()['sku'];

    foreach ($skus as $pos => $sku) {

      $exist = $this->validateSku($sku);

      if (!$exist) {
        $form_state->setErrorByName('sku', $this->t('SKU: @sku doesn\'t exist.', ['@sku' => $sku]));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $op = $form_state->getValue('op');
    $des = $form_state->getValue('description');
    $location_id = $form_state->getValue('location');
    $skus = $form_state->getUserInput()['sku'];
    $quantities = $form_state->getUserInput()['qty'];

    foreach ($skus as $pos => $sku) {
      $stock = $this->getStock($sku, $location_id);

      if ($op == 'Sell' || $op == 'Move' || $op == 'Delete') {
        $quantity = abs($quantities[$pos]) * -1;
      } else if ($op == 'Return') {
        $quantity = abs($quantities[$pos]);
      } else if ($op == 'Fill') {
        $quantity = abs($quantities[$pos]);

        // If there is no stock entity set up at the specific location, creates one
        if (!isset($stock)) {
          $stock = Stock::create([
            'type' => 'default',
            'langcode' => 'en',
            'quantity' => $quantity,
            'stock_location' => $location_id,
          ]);
          $stock->save();

          // Update the product variation for the entity reference
          $query = \Drupal::entityQuery('commerce_product_variation');
          $variationIDs = $query->condition('sku', $sku)->execute();
          $productVariation = \Drupal::entityTypeManager()->getStorage('commerce_product_variation')->load(current($variationIDs));
          $productVariation->stock->appendItem($stock);
          $productVariation->save();
          break;
        }
      }
      if ($des == '') {
        $des = $op;
      }
      $stock->setChangeReason($des);
      $stock->setQuantity($stock->getQuantity() + $quantity)->save();
    }

    $_SESSION['commerce_stock_movement_form_location_id'] = $location_id;

    drupal_set_message($this->t('Operation: ' . $op . ' succeeded!'));

  }

  /**
   * If a sku exists in database.
   *
   * @param $sku
   */
  protected function validateSku($sku) {
    $result = \Drupal::entityQuery('commerce_product_variation')
      ->condition('sku', $sku)
      ->condition('status', 1)
      ->execute();

    return $result ? TRUE : FALSE;
  }

  /**
   *
   * @return \Drupal\commerce_stock\Entity\StockInterface
   */
  protected function getStock($sku, $location_id) {
    $connection = Database::getConnection('default', NULL);
    $query = $connection->select('commerce_product_variation__stock', 'cs');
    $query->join('commerce_product_variation_field_data', 'cr', 'cr.variation_id=cs.entity_id');
    $query->join('commerce_stock_field_data', 'csf', 'csf.stock_id=cs.stock_target_id');
    $query->fields('cs', ['stock_target_id']);
    $query->condition('cr.sku', $sku);
    $query->condition('csf.stock_location', $location_id);

    $stock_id = $query->execute()->fetchField();

    return \Drupal::entityTypeManager()->getStorage('commerce_stock')->load($stock_id);
  }

}
