<?php

namespace Drupal\commerce_stock;

interface EntityStockUpdateInterface {
  public function createTransaction($product_id, $location_id, $zone, $qry, $cost);
}