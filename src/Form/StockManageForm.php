<?php

namespace Drupal\commerce_stock\Form;


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
   * StockManageForm constructor.
   */
  public function __construct(EntityTypeManager $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'stock_manage_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $product_variation_id = NUll) {
    $product_variant = $this
      ->entityTypeManager
      ->getStorage('commerce_product_variation')
      ->load($product_variation_id);

    $stock_ids = $product_variant->get('stock')->getValue();

    $target_ids = [];
    foreach($stock_ids as $stock_id) {
      $target_ids[] = $stock_id['target_id'];
    }

    $stocks = $this
      ->entityTypeManager
      ->getStorage('commerce_stock')
      ->loadMultiple($target_ids);

    $form['stocks'] = [
      '#type' => 'table',
      '#caption' => $this->t('Stocks'),
      '#header' => array('ID', 'Location', 'Quantity'),
      '#empty' => $this->t('No stock belongs to this variant.'),
    ];

    if ($stocks) {
      $form_state->addBuildInfo('stocks', $stocks);

      foreach ($stocks as $stock_id => $stock) {
        $form['stocks'][$stock_id]['stock_id'] = [
          '#type' => 'markup',
          '#markup' => $stock_id,
        ];

        $form['stocks'][$stock_id]['location_name'] = [
          '#type' => 'markup',
          '#markup' => $stock->getStockLocation()->getLocationName(),
        ];

        $form['stocks'][$stock_id]['quantity'] = [
          '#type' => 'number',
          '#min' => 0,
          '#default_value' => $stock->getQuantity(),
        ];
      }
    }

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    );


    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    foreach ($form_state->getValue('stocks') as $stock_id => $stock) {
      if ((float) $stock['quantity'] < 0) {
        $form_state->setErrorByName('stocks][' . $stock_id . '][quantity', $this->t('Quantity must be positive.'));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->getValue('stocks') as $stock_id => $stock) {
      $stock_entity = $form_state->getBuildInfo()['stocks'][$stock_id];
      if ($stock['quantity'] != $stock_entity->getQuantity()) {
        $stock_entity->setQuantity($stock['quantity'])->save();
      }
    }

    drupal_set_message($this->t('Stocks has been saved.'));
  }

}
