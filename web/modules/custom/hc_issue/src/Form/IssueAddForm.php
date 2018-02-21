<?php

namespace Drupal\hc_issue\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CloseModalDialogCommand;
use Drupal\Core\Ajax\PrependCommand;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\hc_issue\IssueEditFormBase;

/**
 * Class IssueAddForm.
 */
class IssueAddForm extends IssueEditFormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'issue_add_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildEditForm($form, $form_state);

    if (\Drupal::request()->isXmlHttpRequest()) {
      $form['issue_data']['submit']['#attributes']['class'][] = 'use-ajax-submit';
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $data = [];
    \Drupal::moduleHandler()->loadInclude('hc_issue', 'install', 'hc_issue');
    $fields = hc_issue_schema()['hc_issue']['fields'];

    \Drupal::moduleHandler()->loadInclude('hc_issue', 'inc', 'hc_issue.query');

    foreach ($form_state->getValues() as $key => $value) {
      if (isset($fields[$key])) {
        $data[$key] = $value;
      }
    }

    if (!empty($data)) {
      $data['created'] = time();
      $data['uid'] = \Drupal::currentUser()->id();
      $data['assigned'] = 0;
      hc_issue_db_add_issue($data);
    }

    if (\Drupal::request()->isXmlHttpRequest()) {
      $response = new AjaxResponse();
      $response->addCommand(new CloseModalDialogCommand());

      drupal_set_message('Issue was created.');
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
