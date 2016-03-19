<?php


namespace Drupal\commerce_stock\Controller;

use Drupal\Core\Controller\ControllerBase;


class InventoryController extends ControllerBase {
  public function content() {
    return array(
      '#theme' => 'commerce_stock_inventory_control',
      '#test_var' => $this->t('Hello World'),
    );
  }
}