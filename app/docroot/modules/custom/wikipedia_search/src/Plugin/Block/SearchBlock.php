<?php

namespace Drupal\wikipedia_search\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'SearchBlock' block.
 *
 * @Block(
 *  id = "wikipedia_search_block",
 *  admin_label = @Translation("Wikipedia Search block"),
 * )
 */
class SearchBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];

    $build['search'] = \Drupal::formBuilder()->getForm('Drupal\wikipedia_search\Form\SearchForm');
    $build['search']['form_id']['#access'] = FALSE;
    $build['search']['form_build_id']['#access'] = FALSE;
    $build['search']['form_token']['#access'] = FALSE;

    return $build;
  }

}
