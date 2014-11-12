<?php

class CommonSenseApiBaseTest extends PHPUnit_Framework_TestCase
{
  protected $api;
  protected $client_id;
  protected $app_id;

  public function __construct()
  {
    require dirname(__FILE__) . '/config.php';

    $this->client_id = $config['client_id'];
    $this->app_id = $config['app_id'];
  }

  public function setUp()
  {
    $this->api = new CommonSenseApi($this->client_id, $this->app_id, TRUE);
  }
}