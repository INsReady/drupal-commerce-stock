<?php
/**
 * Created by PhpStorm.
 * User: edxxu
 * Date: 2/16/16
 * Time: 11:40 PM
 */

/**
 * @file
 * Contains \Drupal\commerce_stock\Controller\CommerceStockController.
 */

namespace Drupal\commerce_stock\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\Controller\EntityViewController;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CommerceStockController extends ControllerBase {

  /**
   * The renderer service.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * Constructs a CommerceStockController object.
   *
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer service.
   */
  public function __construct(RendererInterface $renderer) {
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('renderer')
    );
  }

  /**
   * Displays a commerce_stock revision.
   *
   * @param int $commerce_stock_revision
   *   The commerce_stock revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($commerce_stock_revision) {
    $commerce_stock = $this->entityManager()->getStorage('commerce_stock')->loadRevision($commerce_stock_revision);
    $commerce_stock_view_controller = new EntityViewController($this->entityManager, $this->renderer);
    $page = $commerce_stock_view_controller->view($commerce_stock);
    return $page;
  }

  /**
   * Page title callback for a commerce_stock revision.
   *
   * @param int $commerce_stock_revision
   *   The commerce_stock revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($commerce_stock_revision) {
    $commerce_stock = $this->entityManager()->getStorage('commerce_stock')->loadRevision($commerce_stock_revision);
    return $this->t('Revision of %title from %date', array('%title' => $commerce_stock->label(), '%date' => format_date($commerce_stock->get('revision_timestamp')->value())));
  }
}