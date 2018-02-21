<?php

namespace Drupal\hc_issue;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\hc_issue\HcCategory as CA;

/**
 * Class IssueAddForm.
 */
abstract class IssueEditFormBase extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function buildEditForm(array $form, FormStateInterface $form_state) {
    $form['category'] = [
      '#required' => TRUE,
      '#type' => 'select',
      '#options' => [
        CA::CA_MIND => $this->t('Mind'),
        CA::CA_PRODUCT => $this->t('Product'),
        CA::CA_PROMOTION => $this->t('Promotion'),
        CA::CA_SALES => $this->t('Sales'),
        CA::CA_TEAM => $this->t('Team'),
        CA::CA_SYSTEM => $this->t('System'),
        CA::CA_OTHER => $this->t('Other...'),
      ],
      '#title' => $this->t('Category'),
    ];
    $form['issue_data'] = [
      '#type' => 'container',
      '#states' => [
        'visible' => [
          [':input[name="category"]' => ['value' => CA::CA_MIND]],
          'or',
          [':input[name="category"]' => ['value' => CA::CA_OTHER]],
        ],
        /*'invisible' => [
          ':input[name="category"]' => ['value' => '']
        ],*/
      ],
    ];
    $form['issue_data']['subject'] = [
      '#required' => TRUE,
      '#type' => 'textfield',
      '#title' => $this->t('Subject'),
      '#maxlength' => 255,
      '#size' => 64,
      '#weight' => '0',
    ];
    $form['issue_data']['description'] = [
      '#required' => TRUE,
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
    ];
    $form['issue_data']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

}
