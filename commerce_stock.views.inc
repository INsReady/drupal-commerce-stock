<?php

/**
 * Implementation of hook_views_data()
 */
function commerce_stock_views_data() {
  $data['views']['stock_management'] = array(
    'title' => t('Stock management form'),
    'help' => t('Provide a form to manage stock.'),
    'area' => array(
      'id' => 'stock_management',
    ),
  );

  return $data;
}