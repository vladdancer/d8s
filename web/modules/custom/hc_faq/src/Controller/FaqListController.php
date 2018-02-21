<?php

namespace Drupal\hc_faq\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class FaqListController.
 */
class FaqListController extends ControllerBase {

  /**
   * Overview.
   *
   * @return string
   *   Return Hello string.
   */
  public function overview($request, $faq_id) {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: overview')
    ];
  }

}
