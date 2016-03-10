<?php

namespace Drupal\commerce_stock\Entity;

use Drupal\commerce_stock\Entity\EntityStockUpdateInterface;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\entity\EntityKeysFieldsTrait;

/**
 * Defines the stock entity class.
 *
 * @ContentEntityType(
 *   id = "commerce_stock",
 *   label = @Translation("Commerce stock"),
 *   handlers = {
 *     "storage" = "Drupal\commerce\CommerceContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "default" = "Drupal\Core\Entity\ContentEntityForm",
 *     },
 *     "inline_form" = "Drupal\commerce_stock\Form\StockInlineForm",
 *     "translation" = "Drupal\content_translation\ContentTranslationHandler"
 *   },
 *   admin_permission = "administer stock entity",
 *   fieldable = TRUE,
 *   translatable = TRUE,
 *   base_table = "commerce_stock",
 *   data_table = "commerce_stock_field_data",
 *   entity_keys = {
 *     "id" = "stock_id",
 *     "bundle" = "type",
 *     "langcode" = "langcode",
 *   },
 *   bundle_entity_type = "commerce_stock_type",
 *   field_ui_base_route = "entity.commerce_stock_type.edit_form",
 * )
 */
class Stock extends ContentEntityBase implements StockInterface, EntityStockUpdateInterface {

  use EntityChangedTrait, EntityKeysFieldsTrait;

  /**
   * {@inheritdoc}
   */
  public function getStockLocation() {
    return $this->get('stock_location')->entity;
  }

  /**
   * {@inhreitdoc}
   */
  public function getQuantity() {
    return $this->get('quantity')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setQuantity($quantity) {
    $this->set('quantity', $quantity);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setChangeReason($reason) {
    $this->changeReason = $reason;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = self::entityKeysBaseFieldDefinitions($entity_type);

    // Stock on hand quantity
    $fields['quantity'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Quantity'))
      ->setDescription(t('The quantity of the product.'))
      ->setRequired(TRUE)
      ->setRevisionable(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => 0,
      ])
      ->setSetting('placeholder', '')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);


    $fields['stock_location'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Stock Location'))
      ->setDescription(t('The list of stock locations'))
      ->setSetting('target_type', 'commerce_stock_location')
      ->setRequired(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::postSave($storage);

    if ($this->isNew()) {
      $original_stock = 0;
      $this->isNew = TRUE;
    }
    else {
      $original_stock = $this->original->get('quantity')->value;
    }

    $new_stock = $this->get('quantity')->value;

    if ($original_stock != $new_stock) {
      $this->stock_delta = $new_stock - $original_stock;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function postSave(EntityStorageInterface $storage, $update = TRUE) {
    parent::postSave($storage, $update);

    if (isset($this->stock_delta)) {
      if (!$this->isNew) {
        $query = \Drupal::entityQuery('commerce_product_variation');

        $result = $query->condition('stock', $this->id())->execute();

        if ($result) {
          $stock_location_id = $this->get('stock_location')->entity->id();
          $commerce_variation_id = reset($result);
          if ($this->changeReason == NULL) {
            $this->changeReason = 'Stock Updated on Product Form.';
          }
          $this->createTransaction($commerce_variation_id, $this->id(), $stock_location_id, $this->stock_delta, 0, $this->changeReason);
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function createTransaction($product_variation_id, $stock_id, $location_id, $qty, $uid = 0, $des = '') {
    if (!$uid) {
      $uid = \Drupal::currentUser()->id();
    }

    $movement = $this->entityTypeManager()
      ->getStorage('commerce_stock_movement')
      ->create([
        'variation_id' => $product_variation_id,
        'stock_id' => $stock_id,
        'qty' => $qty,
        'location_id' => $location_id,
        'uid' => $uid,
        'description' => $des,
      ]);

    $movement->save();
  }

}
