<?php

class CSAPI {
  protected $client_id;
  protected $app_id;
  public $host;

  /**
   *
   */
  public function __construct($client_id, $app_id, $mode = NULL) {
    $this->client_id = $client_id;
    $this->app_id = $app_id;

    // Set the API server to be called.
    $this->host = 'https://api.commonsense.org';
    if ($mode == 'dev') {
      $this->host = 'https://api-dev.commonsense.org';
    }
  }

  /**
   * Make a request to the API.
   */
  public function request($path, $options = array()) {
    // Generate query params.
    $params = array(
      'clientId' => $this->client_id,
      'appId' => $this->app_id,
    );

    // Override default parameters.
    $params = array_merge($params, $options);

    // Generate the url.
    $url = $this->host . '/' . $path . '?' . http_build_query($params);

    // Make the call to the API.
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);

    return json_decode($result);
  }

  /**
   * Get a list of education products.
   */
  public function education_products($options) {

  }

  /**
   * Get details of an education products.
   */
  public function education_product($product_id, $options) {

  }
}