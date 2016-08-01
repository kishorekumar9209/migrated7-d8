<?php

/**
 * @file
 * Contains \Drupal\my_migration\Plugin\migrate\source\Node_Blogs.
 */

namespace Drupal\my_migration\Plugin\migrate\source;

use Drupal\migrate\Row;
use Drupal\node\Plugin\migrate\source\d7\Node;

/**
 * Drupal 7 node source from database.
 *
 * @MigrateSource(
 *   id = "my_migration_blog",
 *   source_provider = "node"
 * )
 */
class Node_Blog extends Node {

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    // Destination conent type.
    if (isset($this->migration->get('destination')['node_type'])) {
      $row->setSourceProperty('type', $this->migration->get('destination')['node_type']);
    }
    // Get Field API field values.
    $nid = $row->getSourceProperty('nid');
    $vid = $row->getSourceProperty('vid');
    foreach (array_keys($this->getFields('node', $row->getSourceProperty('type'))) as $field) {
      $row->setSourceProperty($field, $this->getFieldValues('node', $field, $nid, $vid));
    }
    // Taxonomy tags.
    $field_tags_tid = [];
    foreach ($row->getSourceProperty('field_tags_tid') as $item) {
      $field_tags_tid[] = $item['tid'];
    }
    $row->setSourceProperty('field_tags', $field_tags_tid);
    return parent::prepareRow($row);
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = parent::fields();
    $fields += [
      'field_tags' => $this->t('Tags Terms'),
    ];
    return $fields;
  }

}
