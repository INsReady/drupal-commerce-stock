<?php

namespace Drupal\commerce_stock\Plugin\views\filter;

use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\filter\InOperator;
use Drupal\views\ViewExecutable;

/**
 * Filter by country.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("commerce_stock_location")
 */
class StockLocation extends InOperator {

  /**
   * {@inheritdoc}
   */
  public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL) {
    parent::init($view, $display, $options);
    $this->valueTitle = t('Available Stock Locations');
    $this->definition['options callback'] = array($this, 'generateOptions');
  }

  /**
   * Helper function that generates the options.
   * @return array
   */
  public function generateOptions() {
    $locations = \Drupal::entityTypeManager()->getStorage('commerce_stock_location')->loadMultiple();

    $options = [];
    foreach ($locations as $lid => $location) {
      $options[$lid] = $location->get('name')->value;
    }

    return $options;
  }

}