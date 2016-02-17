<?php
/**
 * Created by PhpStorm.
 * User: edxxu
 * Date: 2/17/16
 * Time: 8:56 PM
 */

namespace Drupal\commerce_stock\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

class CommerceStockLocationForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $form_state->setRedirect('entity.commerce_stock_location.collection');
    $location = $this->getEntity();
    $location->save();
  }
}