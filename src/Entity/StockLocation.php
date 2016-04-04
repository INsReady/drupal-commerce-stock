<?php

namespace Drupal\commerce_stock\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Field\FieldStorageDefinitionInterface;

/**
 * Defines the commerce_stock_location entity class.
 *
 * @ContentEntityType(
 *   id = "commerce_stock_location",
 *   label = @Translation("Commerce stock location"),
 *   handlers = {
 *     "storage" = "Drupal\commerce\CommerceContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "views_data" = "Drupal\commerce_stock\StockLocationViewsData",
 *     "list_builder" = "Drupal\commerce_stock\Entity\Controller\StockLocationListBuilder",
 *     "form" = {
 *       "add" = "Drupal\commerce_stock\Form\StockLocationForm",
 *       "edit" = "Drupal\commerce_stock\Form\StockLocationForm",
 *       "delete" = "Drupal\commerce_stock\Form\StockLocationDeleteForm",
 *     },
 *     "translation" = "Drupal\content_translation\ContentTranslationHandler"
 *   },
 *   admin_permission = "administer stock entity",
 *   fieldable = TRUE,
 *   translatable = TRUE,
 *   base_table = "commerce_stock_location",
 *   data_table = "commerce_stock_location_field_data",
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
class StockLocation extends ContentEntityBase implements StockLocationInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public function getLocationName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getManager() {
    return $this->get('uid')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function setManager(UserInterface $account) {
    $this->set('uid', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getManagerId() {
    return $this->get('uid')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setManagerId($uid) {
    $this->set('uid', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Stock Location Name'))
      ->setDescription(t('The name of the stock location.'))
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

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Manager(s)'))
      ->setDescription(t('Manager(s) at this stock location .'))
      ->setDefaultValueCallback('Drupal\commerce_store\Entity\Store::getCurrentUserId')
      ->setSetting('target_type', 'user')
      ->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED)
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 50,
      ]);

    return $fields;
  }
}
