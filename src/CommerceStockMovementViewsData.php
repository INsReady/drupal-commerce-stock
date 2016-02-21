<?php

namespace Drupal\commerce_stock;

use Drupal\views\EntityViewsData;

/**
 * Provides the views data for the commerce_stock_movement entity type.
 */
class CommerceStockMovementViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {

    $data = parent::getViewsData();

    // Add the relationships
    $data['commerce_stock_movement_field_data']['variant_id']['relationship']['id'] = 'standard';
    $data['commerce_stock_movement_field_data']['variant_id']['relationship']['base'] = 'commerce_product_variation_field_data';
    $data['commerce_stock_movement_field_data']['variant_id']['relationship']['base field'] = 'variation_id';
    $data['commerce_stock_movement_field_data']['variant_id']['relationship']['title'] = $this->t('Product Variation');
    $data['commerce_stock_movement_field_data']['variant_id']['relationship']['label'] = $this->t('Get the product variations data.');

    return $data;
  }
}
