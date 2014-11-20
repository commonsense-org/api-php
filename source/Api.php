<?php

require 'api/Education.php';
require 'api/Media.php';

/**
 * Driver for the Common Sense API.
 *
 * @see https://github.com/commonsense-org/api-php
 */
class CommonSenseApi {
  const PLATFORM_EDUCATION = 'education';
  const PLATFORM_MEDIA = 'media';

  public $host;

  protected $client_id;
  protected $app_id;
  protected $is_dev = FALSE;
  protected $platform;
  protected $version;

  /**
   * Sets up the API instance.
   *
   * @param string
   *   a Common Sense partner client ID.
   * @param string
   *   a Common Sense partner app ID.
   * @param boolean
   *   TRUE|FALSE whether the Common Sense API dev server should be used for the tests.
   */
  public function __construct($client_id, $app_id, $is_dev = FALSE) {
    $this->client_id = $client_id;
    $this->app_id = $app_id;
    $this->is_dev = $is_dev;

    // Set the API server to be called.
    $this->host = 'https://api.commonsense.org';
    if ($this->is_dev === TRUE) {
      $this->host = 'https://api-dev.commonsense.org';
    }
  }

  /**
   * Handles magic methods for list and item API calls.
   *
   * The following patterns are defined for calling the API list and item calls.
   *   get_<type>_list($options)
   *   get_<type>_item($id, $options)
   *
   * where <type> is the API endpoint.
   *
   * Example:
   *   URL: http://api.commonsense.org/v3/education/products/...
   *   Magic methods: products_list($options) and products_item($id, $options)
   */
  public function __call($name, $args) {
    preg_match('/get_([a-zA-Z_]+)_([a-zA-Z]+)/', $name, $matches);

    if (count($matches) > 0) {
      $type = $matches[1];
      $suffix = $matches[count($matches) - 1];

      if ($suffix === 'list') {
        // Methods that have a suffix of _list().
        $type = $matches[1];
        $options = count($args) > 0 && $args[0] ? $args[0] : array();
        return $this->get_list($type, $options);
      }
      elseif ($suffix === 'item') {
        // Methods that have a suffix of _item().
        $type = $matches[1];
        $id = array_shift($args);
        $options = count($args) > 0 && $args[0] ? $args[0] : array();
        return $this->get_item($type, $id, $options);
      }
    }
    elseif (method_exists($this, $name)) {
      // Call the existing method.
      return $this->{$name}();
    }
  }

  /**
   * Returns a singleton instance of this class.
   *
   * @param string
   *   the class name that is to be instantiated (CommonSenseApiMedia or CommonSenseApiEducation).
   * @return mixed
   *   a singleton instance of a media or education API object.
   */
  public static function instance(CommonSenseApi $api, $class_name) {
    static $instance = array();

    if (!array_key_exists($class_name, $instance)) {
      $instance[$class_name] = new $class_name($api->client_id, $api->app_id, $api->is_dev);
    }

    return $instance[$class_name];
  }

  /**
   * Make a request to the API.
   *
   * @param string
   *   the URL path of the API endpoint.
   * @param array
   *   filter options that vary depending on the API endpoint.
   * @return object
   *   the JSON response data converted into a native object.
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
   * Get a singleton instance of CommonSenseApiEducation.
   *
   * @return CommonSenseApiEducation
   *   a singleton instance of the education API object.
   */
  public function education() {
    return CommonSenseApiEducation::instance($this, 'CommonSenseApiEducation');
  }

  /**
   * Get a singleton instance of CommonSenseApiMedia.
   *
   * @return CommonSenseApiMedia
   *   a singleton instance of the media API object.
   */
  public function media() {
    return CommonSenseApiMedia::instance($this, 'CommonSenseApiMedia');
  }

  /**
   * Abstract data item fetcher.
   *
   * @param string
   *   the Common Sense endpoint path partial.
   * @param id
   *   the system ID of the item.
   * @param array
   *   filter options that the Common Sense API supports.
   * @return object
   *   the API response data converted to an object.
   */
  public function get_item($endpoint, $id, $options = array()) {
    $path = 'v' . $this->version . '/' . $this->platform . '/' . $endpoint . '/' . $id;
    return $this->request($path, $options);
  }

  /**
   * Abstract data list fetcher.
   *
   * @param string
   *   the Common Sense endpoint path partial.
   * @param id
   *   the system ID of the item.
   * @param array
   *   filter options that the Common Sense API supports.
   * @return object
   *   the API response data converted to an object
   */
  public function get_list($endpoint, $options = array()) {
    $path = 'v' . $this->version . '/' . $this->platform . '/' . $endpoint;
    return $this->request($path, $options);
  }
}
