<?php

/**
 * @file
 * Contains \Drupal\commerce_stock\Form\CommerceStockTypeDeleteForm.
 */

namespace Drupal\commerce_stock\Form;

use Drupal\Core\Entity\EntityDeleteForm;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Builds the form to delete a stock type.
 */
class CommerceStockTypeDeleteForm extends EntityDeleteForm {

  /**
   * The query factory to create entity queries.
   *
   * @var \Drupal\Core\Entity\Query\QueryFactory
   */
  protected $queryFactory;

  /**
   * Constructs a new StockTypeDeleteForm object.
   *
   * @param \Drupal\Core\Entity\Query\QueryFactory $query_factory
   *    The entity query object.
   */
  public function __construct(QueryFactory $query_factory) {
    $this->queryFactory = $query_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.query')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $stock_count = $this->queryFactory->get('commerce_stock')
      ->condition('type', $this->entity->id())
      ->count()
      ->execute();
    if ($stock_count) {
      $caption = '<p>' . $this->formatPlural($stock_count, '%type is used by 1 product variation on your site. You can not remove this stock type until you have removed all of the %type stock.', '%type is used by @count product variations on your site. You may not remove %type until you have removed all of the %type stock.', ['%type' => $this->entity->label()]) . '</p>';
      $form['#title'] = $this->getQuestion();
      $form['description'] = ['#markup' => $caption];
      return $form;
    }

    return parent::buildForm($form, $form_state);
  }

}
