<?php

namespace Drupal\commerce_stock\Form;


use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class StockManageForm extends FormBase {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * StockManageForm constructor.
   */
  public function __construct(EntityTypeManager $entityTypeManager, Connection $connection) {
    $this->entityTypeManager = $entityTypeManager;
    $this->connection = $connection;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'stock_manage_form';
  }

  /**
   * If a sku exists in database.
   *
   * @param $sku
   */
  protected function validateSku($sku) {
    $result = $this->connection
      ->select('commerce_product_variation_field_data', 'cpv')
      ->fields('cpv')
      ->condition('cpv.sku', $sku)
      ->execute()
      ->fetchCol();


    return $result ? TRUE : FALSE;
  }

  /**
   *
   * @return \Drupal\commerce_stock\Entity\StockInterface
   */
  protected function getStock($sku, $location_id) {
    $query = $this->connection->select('commerce_product_variation__stock', 'cs');
    $query->join('commerce_product_variation_field_data', 'cr', 'cr.variation_id=cs.entity_id');
    $query->join('commerce_stock_field_data', 'csf', 'csf.stock_id=cs.stock_target_id');
    $query->fields('cs', ['stock_target_id']);
    $query->condition('cr.sku', $sku);
    $query->condition('csf.stock_location', $location_id);

    $stock_id = $query->execute()->fetchField();

    return $this->entityTypeManager->getStorage('commerce_stock')->load($stock_id);
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['sku'] = [
      '#type' => 'textfield',
      '#autocomplete_route_name' => 'commerce_stock.sku_autocomplete',
      '#required' => TRUE,
      '#title' => $this->t('SKU'),
    ];

    $locations = $this->entityTypeManager->getStorage('commerce_stock_location')->loadMultiple();

    $options = [];
    foreach ($locations as $lid => $location) {
      $options[$lid] = $location->get('name')->value;
    }

    $form['location'] = [
      '#type' => 'select',
      '#options' => $options,
      '#required' => TRUE,
      '#title' => $this->t('Location'),
    ];
    $form['quantity'] = [
      '#type' => 'number',
      '#default_value' => 0,
      '#required' => TRUE,
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
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $sku = $form_state->getValue('sku');
    $location_id = $form_state->getValue('location');

    if (!is_numeric($form_state->getValue('quantity'))) {
      $form_state->setErrorByName('quantity', $this->t('Quantity must be a number.'));
    }

    if (!$this->validateSku($sku)) {
      $form_state->setErrorByName('sku', $this->t('SKU @sku doesn\'t exist.', ['@sku' => $sku]));
    }

    if (!$this->getStock($sku, $location_id)) {
      $form_state->setErrorByName('sku', $this->t('Stock you select doesn\'t exist.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $sku = $form_state->getValue('sku');
    $location_id = $form_state->getValue('location');
    $quantity = $form_state->getValue('quantity');

    $stock = $this->getStock($sku, $location_id);

    $stock->setQuantity($stock->getQuantity() + $quantity)->save();

    drupal_set_message($this->t('Stocks has been saved.'));
  }

}