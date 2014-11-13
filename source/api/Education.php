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
}