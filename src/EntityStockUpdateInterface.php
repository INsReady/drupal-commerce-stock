<?php

namespace Drupal\commerce_stock;

interface EntityStockUpdateInterface {
  public function createTransaction($commerce_variant_id, $location_id, $qry);
}