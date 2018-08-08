<?php

namespace Drupal\wikipedia_search\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Class SearchForm.
 */
class SearchForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'wikipedia_search_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['keywords'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter Search Keywords'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => \Drupal::request()->query->get('keywords'),
    ];

    $form['#action'] = Url::fromRoute('wikipedia_search.search')
      ->toString();

    $form['#cache']['max-age'] = 0;
    $form['#method'] = 'get';
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Search'),
      '#name' => '',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}
