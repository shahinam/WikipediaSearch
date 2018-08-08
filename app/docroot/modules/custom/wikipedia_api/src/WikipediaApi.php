<?php

namespace Drupal\wikipedia_api;

use Drupal\Core\Logger\LoggerChannelFactory;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\ClientInterface;

class WikipediaApi {

  /**
   * The http client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $client;

  /**
   * API Endpoint URL.
   *
   * @var string
   */
  protected $apiEndpoint;

  /**
   * A logger instance.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * WikipediaApi constructor.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   *   The http client.
   */
  public function __construct(ClientInterface $http_client, LoggerChannelFactory $logger) {
    $this->client = $http_client;
    $this->apiEndpoint = 'https://en.wikipedia.org/w/api.php';
    $this->logger = $logger->get('wikipedia_api');
  }

  public function get($options) {

    $options['http_errors'] = FALSE;

    try {
      if ($response = $this->client->request('GET', $this->apiEndpoint, $options)) {
        if ($data = $response->getBody()->getContents()) {
          return json_decode($data, TRUE);
        }
      }
    }
    catch (RequestException $e) {
      $this->logger->error($e->getMessage());
    }
    return FALSE;

  }

  /**
   * Get topic data.
   *
   * @param $str
   *   Topic to search.
   *
   * @return bool|mixed
   *   Topic data or FALSE on error.
   */
  public function topic($str) {
    $props = ['extracts'];
    $query = [
      'action' => 'query',
      'format' => 'json',
      'prop' => implode('|', $props),
      'exintro' => '',
      'titles' => $str,
    ];
    $options = ['query' => $query];

    $data = $this->get($options);

    if (array_key_exists('query', $data) && array_key_exists('pages', $data['query'])) {
      return array_shift($data['query']['pages']);
    }

    return FALSE;
  }

  /**
   * Keyword search.
   *
   * @param $str
   *   Keywords to search.
   *
   * @return array
   *   Search data.
   */
  public function search($str) {
    $query = [
      'action' => 'opensearch',
      'search' => $str,
      'namespace' => '*',
      'limit' => 10,
      'profile' => 'engine_autoselect',
      'format' => 'json',
    ];
    $options = ['query' => $query];

    $data = $this->get($options);
    $items = [];
    foreach ($data[1] as $key => $value) {
      $items[] = [
        'title' => $data[1][$key],
        'extract' => $data[2][$key],
        'link' => $data[3][$key],
      ];
    }

    if ($items) {
      return $items;
    }

    return [];
  }

}
