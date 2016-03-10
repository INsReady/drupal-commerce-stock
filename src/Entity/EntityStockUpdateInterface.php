<?php

namespace Drupal\commerce_stock\Entity;

interface EntityStockUpdateInterface {

  /**
   * @todo Add document
   *
   * @param $product_variation_id
   * @param $stock_id
   * @param $location_id
   * @param $qry
   * @param $uid
   * @param $des: description of the movement
   * @return mixed
   */
  public function createTransaction($product_variation_id, $stock_id, $location_id, $qry, $uid = 0, $des = '');
}
