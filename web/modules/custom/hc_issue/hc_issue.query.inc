<?php

use Drupal\Core\Session\AccountInterface;

define('HC_ISSUE_TABLE', 'hc_issue');

function hc_issue_db_load_issue($issue_id = NULL) {
  $query = \Drupal::database()->select(HC_ISSUE_TABLE, 'i')
    ->fields('i');

  if (!empty($issue_id)) {
    $query->condition('i.iid', $issue_id);
  }

  $results = $query->execute();

  return empty($issue_id)
    ? $results->fetchAllAssoc('iid')
    : $results->fetchObject();
}

function hc_issue_db_add_issue($data) {
  return $query = \Drupal::database()->insert(HC_ISSUE_TABLE)
    ->fields($data)
    ->execute();
}

function hc_issue_db_update_issue($issue_id, $fields) {
  return $query = \Drupal::database()->update(HC_ISSUE_TABLE)
    ->fields($fields)
    ->condition('iid', $issue_id)
    ->execute();
}

function hc_issue_db_user_issues($user_id = NULL) {
  if (!$user_id) {
    $user = \Drupal::currentUser();
  }
  else {
    /* @var \Drupal\user\Entity\User  */
    $storage = \Drupal::entityTypeManager()->getStorage('user');
    $user = $storage->load($user_id);
  }

  if (!($user instanceof AccountInterface)) {
    return NULL;
  }

  $query = \Drupal::database()->select(HC_ISSUE_TABLE, 'i')->fields('i');
  $query->condition('i.uid', $user->id());

  return $query
    ->execute()
    ->fetchAllAssoc('iid');
}
