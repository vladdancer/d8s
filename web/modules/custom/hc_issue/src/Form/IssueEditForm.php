<?php

namespace Drupal\hc_issue\Form;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CloseModalDialogCommand;
use Drupal\Core\Ajax\PrependCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\hc_issue\IssueEditFormBase;

/**
 * Class IssueEditFrom.
 */
class IssueEditForm extends IssueEditFormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'issue_edit_from';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $issue_id = NULL) {

    if (empty($issue_id) || !is_numeric($issue_id)) {
      drupal_set_message('Empty issue id was detected');
      return FALSE;
    }

    \Drupal::moduleHandler()->loadInclude('hc_issue', 'inc', 'hc_issue.query');
    $issue = hc_issue_db_load_issue($issue_id);

    $form = parent::buildEditForm($form, $form_state);

    $form['issue_id'] = [
      '#type' => 'hidden',
      '#value' => $issue_id,
    ];

    $form['category']['#default_value'] = $issue->category;
    $form['issue_data']['subject']['#default_value'] = Xss::filter($issue->subject);
    $form['issue_data']['description']['#default_value'] = Xss::filter($issue->description);

    if (\Drupal::request()->isXmlHttpRequest()) {
      $form['issue_data']['submit']['#attributes']['class'][] = 'use-ajax-submit';
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::moduleHandler()->loadInclude('hc_issue', 'install', 'hc_issue');
    \Drupal::moduleHandler()->loadInclude('hc_issue', 'inc', 'hc_issue.query');

    $fields = hc_issue_schema()['hc_issue']['fields'];
    $data = [];

    foreach ($form_state->getValues() as $key => $value) {
      if (isset($fields[$key])) {
        $data[$key] = $value;
      }
    }
    if (!empty($data)) {
      hc_issue_db_update_issue($form_state->getValue('issue_id'), $data);
    }


    if (\Drupal::request()->isXmlHttpRequest()) {
      $response = new AjaxResponse();
      $response->addCommand(new CloseModalDialogCommand());

      drupal_set_message('Issue was updated.');
      $status_messages = ['#type' => 'status_messages'];

      $response->addCommand(new PrependCommand(
        '.region-content .block-system .content',
        \Drupal::service('renderer')->renderRoot($status_messages)
      ));
      $form_state->setResponse($response);
    }
    else {
      $form_state->setRedirectUrl(Url::fromRoute('hc_issue.user_issues'));
    }

  }

}
