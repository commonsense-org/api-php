<?php

require '../source/Api.php';

class CSAPITest extends PHPUnit_Framework_TestCase
{
  protected $api;
  protected $client_id;
  protected $app_id;

  public function __construct()
  {
    require 'config.php';

    $this->client_id = $config['client_id'];
    $this->app_id = $config['app_id'];
  }

  public function setUp()
  {
    $this->api = new CSAPI($this->client_id, $this->app_id, 'dev');
  }

  public function testInit()
  {
    // Check the mode settings.
    $this->api = new CSAPI($this->client_id, $this->app_id);
    $this->assertEquals($this->api->host, 'https://api.commonsense.org');

    $this->api = new CSAPI($this->client_id, $this->app_id, 'dev');
    $this->assertEquals($this->api->host, 'https://api-dev.commonsense.org');
  }

  public function testRequestNoOptions()
  {
    // Make a request with no options.
    $response = $this->api->request('v3/education/products');
    $this->assertEquals($response->statusCode, 200);
    $this->assertGreaterThan(0, $response->count);
    $this->assertGreaterThan(0, count($response->response));
  }

  public function testRequestSetLimit()
  {
    // Make a request overriding the default options.
    $response = $this->api->request('v3/education/products', array('limit' => 3));
    $this->assertEquals($response->statusCode, 200);
    $this->assertGreaterThan(0, $response->count);
    $this->assertEquals(3, count($response->response));
  }

  public function testRequestPagenation()
  {
    // Get a larger list, then make separate calls with smaller results to test pagenation.
    $response = $this->api->request('v3/education/products', array('limit' => 10));
    $products = $response->response;
    $this->assertEquals($response->statusCode, 200);
    $this->assertGreaterThan(0, $response->count);
    $this->assertEquals(10, count($products));

    // Make pagenated requests for page 1.
    $response = $this->api->request('v3/education/products', array('limit' => 5, 'page' => 1));
    $productsPage1 = $response->response;
    $this->assertEquals($response->statusCode, 200);
    $this->assertGreaterThan(0, $response->count);
    $this->assertEquals(5, count($productsPage1));

    // Check the first page of producdts.
    for ($i = 0; $i < 5; $i++)
    {
      $product = $products[$i];
      $this->assertEquals($product->id, $productsPage1[$i]->id);
      $this->assertEquals($product->title, $productsPage1[$i]->title);
      $this->assertEquals($product->type, $productsPage1[$i]->type);
    }

    // Make pagenated requests for page 2.
    $response = $this->api->request('v3/education/products', array('limit' => 5, 'page' => 2));
    $productsPage2 = $response->response;

    // Check the second page of producdts.
    for ($i = 0; $i < 5; $i++)
    {
      $product = $products[($i + 5)];
      $this->assertEquals($product->id, $productsPage2[$i]->id);
      $this->assertEquals($product->title, $productsPage2[$i]->title);
      $this->assertEquals($product->type, $productsPage2[$i]->type);
    }
  }

  public function testEducationProduct()
  {

  }

  public function testEducationProducts()
  {

  }
}