<?php

namespace Drupal\commerce_stock\Form;

use Drupal\inline_entity_form\Form\EntityInlineForm;

/**
 * Defines the inline form for product stock.
 */
class StockInlineForm extends EntityInlineForm {
  /**
   * {@inheritdoc}
   */
  public function tableFields($bundles) {
    $fields = parent::tableFields($bundles);
    $fields['quantity'] = [
      'type' => 'field',
      'label' => t('Quantity'),
      'weight' => 200,
    ];

    return $fields;
  }
}
