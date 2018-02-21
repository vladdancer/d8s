<?php

namespace Drupal\hc_issue\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;

/**
 * Class UserIssueController.
 */
class UserIssueController extends ControllerBase {

  /**
   * Listissue.
   *
   * @return string
   *   Return Hello string.
   */
  public function listIssues() {
    $this->moduleHandler()->loadInclude('hc_issue', 'inc', 'hc_issue.query');

    $issues = hc_issue_db_user_issues();

    $add_button = [
      '#theme' => 'links',
      '#links' => [
        [
          'url' => Url::fromRoute('hc_issue.issue_add_page'),
          'title' => $this->t('Add issue'),
          'attributes' => [
            'class' => [
              'button',
              'button-action',
              'button--primary',
              'button--small',
              'use-ajax',
            ],
            'data-dialog-type' => 'modal',
            'data-dialog-options' => '{"width":800,"height":500}',
          ]
        ],
      ],
      '#access' => \Drupal::accessManager()->checkNamedRoute('hc_issue.issue_add_page'),
    ];

    $table = [
      '#type' => 'table',
      '#header' => $this->buildHeader(),
      '#rows' => [],
    ];
    foreach ($issues as $key => $issue) {
      if ($row = $this->buildRow($issue)) {
        $table['#rows'][$key] = $row;
      }
    }

    return [
      'add_button' => $add_button,
      'list' => $table,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['subject'] = $this->t('Subject');
    $header['create'] = $this->t('Created');
    $header['operations'] = $this->t('Operations');
    return $header;
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow($issue) {
    $row['subject'] = $issue->subject;
    $row['created'] = \Drupal::service('date.formatter')->format($issue->created);
    $row['operations']['data'] = $this->buildOperations($issue);
    return $row;
  }

  protected function buildOperations($issue) {
    return [
      '#type' => 'operations',
      '#links' => [
        'view' => [
          'title' => $this->t('View'),
          'url' => Url::fromRoute('hc_issue.view', ['issue_id' => $issue->iid]),
        ],
        'edit' => [
          'title' => $this->t('Edit'),
          'url' => Url::fromRoute('hc_issue.edit', ['issue_id' => $issue->iid]),
          'attributes' => [
            'class' => ['use-ajax'],
            'data-dialog-type' => 'modal',
            'data-dialog-options' => '{"width":800,"height":500}',
          ],
        ],
      ],
    ];
  }

}
