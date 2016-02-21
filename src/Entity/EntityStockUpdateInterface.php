<?php

namespace Drupal\commerce_stock\Entity;

interface EntityStockUpdateInterface {
  public function createTransaction($commerce_variant_id, $location_id, $qry, $uid);
}
