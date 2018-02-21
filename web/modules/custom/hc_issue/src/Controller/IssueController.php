<?php

namespace Drupal\hc_issue\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class IssueController.
 */
class IssueController extends ControllerBase {

  /**
   * View.
   *
   * @return string
   *   Return Hello string.
   */
  public function view($issue_id) {
    if (empty($issue_id) || !is_numeric($issue_id)) {
      drupal_set_message('Empty issue id was detected');
      return FALSE;
    }

    \Drupal::moduleHandler()->loadInclude('hc_issue', 'inc', 'hc_issue.query');
    $issue = hc_issue_db_load_issue($issue_id);

    return [
      'subject' => [
        '#type' => 'markup',
        '#markup' => 'Issue subject: ' . $issue->subject . '<br>',
      ],
      'description' => [
        '#type' => 'markup',
        '#markup' => 'Issue description: ' . $issue->description . '<br>',
      ],
    ];
  }

}
