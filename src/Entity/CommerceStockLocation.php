<?php
/**
 * Created by PhpStorm.
 * User: edxxu
 * Date: 2/17/16
 * Time: 5:33 PM
 */

/**
 * @file
 * Contains \Drupal\commerce_stock\Entity\CommerceStockLocation.
 */

namespace Drupal\commerce_stock\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\entity\EntityKeysFieldsTrait;

/**
 * Defines the commerce_stock_location entity class.
 *
 * @ContentEntityType(
 *   id = "commerce_stock_location",
 *   label = @Translation("Commerce stock location"),
 *   handlers = {
 *     "storage" = "Drupal\commerce\CommerceContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "list_builder" = "Drupal\commerce_stock\Entity\Controller\CommerceStockLocationListBuilder",
 *     "form" = {
 *       "add" = "Drupal\commerce_stock\Form\CommerceStockLocationForm",
 *       "edit" = "Drupal\commerce_stock\Form\CommerceStockLocationForm",
 *       "delete" = "Drupal\commerce_stock\Form\CommerceStockLocationDeleteForm",
 *     },
 *     "translation" = "Drupal\content_translation\ContentTranslationHandler"
 *   },
 *   admin_permission = "administer stock entity",
 *   fieldable = TRUE,
 *   translatable = TRUE,
 *   base_table = "commerce_stock_location",
 *   data_table = "commerce_stock_field_location_data",
 *   entity_keys = {
 *     "id" = "stock_location_id",
 *     "label" = "name",
 *     "langcode" = "langcode",
 *     "uuid" = "uuid",
 *   },
 *   field_ui_base_route = "entity.commerce_stock_location.edit_form",
 *   links = {
 *     "collection" = "/admin/commerce/commerce-stock-locations",
 *     "add-form" = "/admin/commerce/commerce-stock-location/add",
 *     "edit-form" = "/admin/commerce/commerce-stock-location/{commerce_stock_location}/edit",
 *     "delete-form" = "/admin/commerce/commerce-stock-location/{commerce_stock_location}/delete",
 *   }
 * )
 */
class CommerceStockLocation extends ContentEntityBase {

  use EntityChangedTrait, EntityKeysFieldsTrait;

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = self::entityKeysBaseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Comerce stock location name'))
      ->setDescription(t('The name of the Commerce stock location.'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -6,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -6,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }
}