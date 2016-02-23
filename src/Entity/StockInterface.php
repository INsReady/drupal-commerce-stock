<?php

namespace Drupal\commerce_stock\Entity;

interface StockInterface {
  public function getStockLocation();

  public function getQuantity();

  public function setQuantity($quantity);
}