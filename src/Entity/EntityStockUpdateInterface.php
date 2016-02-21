<?php

namespace Drupal\commerce_stock\Entity;

interface EntityStockUpdateInterface {

  /**
   * @todo Add document
   *
   * @param $commerce_variant_id
   * @param $location_id
   * @param $qry
   * @param $uid
   * @return mixed
   */
  public function createTransaction($commerce_variant_id, $location_id, $qry, $uid = 0);
}
