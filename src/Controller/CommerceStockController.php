<?php

namespace Drupal\commerce_stock\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CommerceStockController extends ControllerBase {

  public function handleAutocomplete(Request $request) {

    if ($input = $request->query->get('q')) {
      $result = \Drupal::entityQuery('commerce_product_variation')
        ->condition('sku', $input, 'LIKE %...%')
        ->condition('status', 1)
        ->range(0, 10)
        ->execute()->fetchCol();
    }

    return new JsonResponse($result);
  }
}