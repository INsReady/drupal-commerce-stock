<?php

namespace Drupal\commerce_stock\Entity;


interface EntityStockCheckInterface {
  public function getStockLevel($product_id, $locations);
  public function getIsInStock($product_id, $locations);
  public function getIsAlwaysInStock($product_id);
  public function getIsStockManaged($product_id);
  public function getLocationList();
}
