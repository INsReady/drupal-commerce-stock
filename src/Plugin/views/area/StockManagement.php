<?php

namespace Drupal\commerce_stock\Plugin\views\area;

use Drupal\Core\Form\FormBuilder;
use Drupal\views\Plugin\views\area\AreaPluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @ingroup views_area_handlers
 *
 * @ViewsArea("stock_management")
 */
class StockManagement extends AreaPluginBase {

  protected $FormBuilder;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, FormBuilder $form_builder) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->FormBuilder = $form_builder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('form_builder')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function render($empty = FALSE) {
    $form = $this->FormBuilder->getForm('\Drupal\commerce_stock\Form\StockManageForm');

    return $form;
  }
}