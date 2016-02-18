<?php

namespace Drupal\commerce_stock\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
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
 *   revision_table = "commerce_stock_revision",
 *   revision_data_table = "commerce_stock_field_revision",
 *   entity_keys = {
 *     "id" = "stock_id",
 *     "revision" = "vid",
 *     "bundle" = "type",
 *     "langcode" = "langcode",
 *     "uuid" = "uuid",
 *   },
 *   bundle_entity_type = "commerce_stock_type",
 *   field_ui_base_route = "entity.commerce_stock_type.edit_form",
 *   links = {
 *     "revision" = "/commerce-stock/{commerce_stock}/revisions/{commerce_stock_revision}/view",
 *   }
 * )
 */
class Stock extends ContentEntityBase {

  use EntityChangedTrait, EntityKeysFieldsTrait;


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
}
