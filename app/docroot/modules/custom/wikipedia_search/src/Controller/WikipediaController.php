<?php

namespace Drupal\wikipedia_search\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\wikipedia_api\WikipediaApi;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class WikipediaController.
 */
class WikipediaController extends ControllerBase {

  /**
   * Drupal\wikipedia_api\WikipediaApi definition.
   *
   * @var \Drupal\wikipedia_api\WikipediaApi
   */
  protected $wikipediaApi;

  /**
   * Constructs a new WikipediaController object.
   */
  public function __construct(WikipediaApi $wikipedia_api) {
    $this->wikipediaApi = $wikipedia_api;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('wikipedia_api')
    );
  }

  /**
   * Getcontent.
   */
  public function getContent(Request $request, $title = NULL) {
    if ($title) {
      $data = $this->wikipediaApi->topic($title);
      $build = [
        '#theme' => 'article',
        '#data' => $data,
      ];
    }
    elseif ($keywords = $request->query->get('keywords')) {
      $data = $this->wikipediaApi->search($keywords);
      $build = [
        '#theme' => 'search_results',
        '#data' => $data,
      ];
    }
    else {
      $build = [
        '#theme' => 'about',
      ];
    }

    return $build;
  }

  /**
   * @param null $title
   *
   * @return null|string
   */
  public function getTitle(Request $request, $title = NULL) {
    if ($title) {
      return ucwords($title);
    }
    elseif ($keywords = $request->query->get('keywords')) {
      return 'Search results for "' . $keywords . '"';
    }
    else {
      return 'About this page';
    }
  }

}
