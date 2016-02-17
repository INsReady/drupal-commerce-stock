<?php

/**
 * @file
 * Contains \Drupal\commerce_stock\Entity\StockType.
 */

namespace Drupal\commerce_stock\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the stock type entity class.
 *
 * @ConfigEntityType(
 *   id = "commerce_stock_type",
 *   label = @Translation("Commerce stock type"),
 *   handlers = {
 *     "list_builder" = "Drupal\commerce_stock\Entity\Controller\StockTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\commerce_stock\Form\StockTypeForm",
 *       "edit" = "Drupal\commerce_stock\Form\StockTypeForm",
 *       "delete" = "Drupal\commerce_stock\Form\StockTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *       "create" = "Drupal\entity\Routing\CreateHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "commerce_stock_type",
 *   admin_permission = "administer commerce stock types",
 *   bundle_of = "commerce_stock",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *   },
 *   links = {
 *     "add-form" = "/admin/commerce/config/stock-types/add",
 *     "edit-form" = "/admin/commerce/config/stock-types/{commerce_stock_type}/edit",
 *     "delete-form" = "/admin/commerce/config/stock-types/{commerce_stock_type}/delete",
 *     "collection" =  "/admin/commerce/config/stock-types"
 *   }
 * )
 */
class StockType extends ConfigEntityBundleBase {
  /**
   * The stock type id.
   *
   * @var string
   */
  protected $id;
}
