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

    // Add the relationship to Product Variation
    $data['commerce_stock_movement_field_data']['variant_id']['relationship']['id'] = 'standard';
    $data['commerce_stock_movement_field_data']['variant_id']['relationship']['base'] = 'commerce_product_variation_field_data';
    $data['commerce_stock_movement_field_data']['variant_id']['relationship']['base field'] = 'variation_id';
    $data['commerce_stock_movement_field_data']['variant_id']['relationship']['title'] = $this->t('Product Variation');
    $data['commerce_stock_movement_field_data']['variant_id']['relationship']['label'] = $this->t('Product Variations');

    // Add the relationship to User
    $data['commerce_stock_movement_field_data']['uid']['relationship']['id'] = 'standard';
    $data['commerce_stock_movement_field_data']['uid']['relationship']['base'] = 'users_field_data';
    $data['commerce_stock_movement_field_data']['uid']['relationship']['base field'] = 'uid';
    $data['commerce_stock_movement_field_data']['uid']['relationship']['title'] = $this->t('Users');
    $data['commerce_stock_movement_field_data']['uid']['relationship']['label'] = $this->t('Users');

    // Add the relationship to Stock Location
    $data['commerce_stock_movement_field_data']['location_id']['relationship']['id'] = 'standard';
    $data['commerce_stock_movement_field_data']['location_id']['relationship']['base'] = 'commerce_stock_location_field_data';
    $data['commerce_stock_movement_field_data']['location_id']['relationship']['base field'] = 'stock_location_id';
    $data['commerce_stock_movement_field_data']['location_id']['relationship']['title'] = $this->t('Stock Locations');
    $data['commerce_stock_movement_field_data']['location_id']['relationship']['label'] = $this->t('Stock Locations');

    return $data;
  }
}
