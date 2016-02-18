<?php

namespace Drupal\commerce_stock\Entity;

use Drupal\commerce_stock\EntityStockUpdateInterface;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\entity\EntityKeysFieldsTrait;

/**
 * Defines the commerce_stock_movement entity class.
 *
 * @ContentEntityType(
 *   id = "commerce_stock_movement",
 *   label = @Translation("Commerce stock movement"),
 *   handlers = {
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "translation" = "Drupal\content_translation\ContentTranslationHandler",
 *     "list_builder" = "Drupal\commerce_stock\Entity\Controller\StockMovementListBuilder",
 *   },
 *   admin_permission = "administer stock entity",
 *   fieldable = TRUE,
 *   translatable = TRUE,
 *   base_table = "commerce_stock_movement",
 *   data_table = "commerce_stock_movement_data",
 *   entity_keys = {
 *     "id" = "mid",
 *     "langcode" = "langcode",
 *     "uuid" = "uuid",
 *   },
 *   field_ui_base_route = "commerce_stock.commerce_stock_movement.settings",
 *   links = {
 *     "collection" = "/admin/commerce/commerce-stock-movement/list",
 *   }
 * )
 */
class StockMovement extends ContentEntityBase {

  use EntityChangedTrait, EntityKeysFieldsTrait;

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = self::entityKeysBaseFieldDefinitions($entity_type);

    $fields['variant_id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Comerce variant id'))
      ->setDescription(t('The id of commerce variant which this movement belongs to.'))
      ->setSetting('unsigned', TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['qty'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('The quantity'))
      ->setDescription(t('The quantity of this movement.'))
      ->setSetting('unsigned', FALSE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }
}
