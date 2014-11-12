<?php

/**
 * Handles calls to the Common Sense Education API.
 */
class CommonSenseApiEducation extends CommonSenseApi {
  public function __construct($client_id, $app_id, $is_dev = FALSE) {
    parent::__construct($client_id, $app_id, $is_dev);

    $this->platform = CommonSenseApi::PLATFORM_EDUCATION;
    $this->version = 3;
  }

  /**
   * Get a list of education products.
   */
  public function products($options = array()) {
    $path = 'v3/education/products';
    return $this->request($path, $options);
  }

  /**
   * Get details of an education products.
   */
  public function product($product_id, $options) {
    $path = 'v3/education/products/' + $product_id;
    return $this->request($path, $options);
  }
}