<?php

/**
 * @file
 * Contains \Drupal\commerce_stock\CommerceStockTypeListBuilder.
 */

namespace Drupal\commerce_stock;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Defines the list builder for stock types.
 */
class CommerceStockTypeListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['name'] = $this->t('Stock type');
    $header['type'] = $this->t('Machine name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['name'] = $this->getLabel($entity);
    $row['type'] = $entity->id();
    return $row + parent::buildRow($entity);
  }

}
