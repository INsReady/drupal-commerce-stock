<?php

namespace Drupal\commerce_stock;

use Drupal\views\EntityViewsData;

/**
 * Provides the views data for the commerce_stock_location entity type.
 */
class StockLocationViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {

    $data = parent::getViewsData();

    // Add custom views filter to stock location
    $data['commerce_stock_location_field_data']['stock_location_id']['filter']['id'] = 'commerce_stock_location';

    return $data;
  }
}
