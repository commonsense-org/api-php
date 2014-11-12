<?php

require dirname(__FILE__) . '/BaseTest.php';
require dirname(__FILE__) . '/../source/Api.php';

class CommonSenseApiTest extends CommonSenseApiBaseTest
{
  /**
   * Tests for instance().
   */
  public function testInstance()
  {
    // Check that a singleton instance is created.
    $instance = CommonSenseApi::instance($this->api, 'CommonSenseApiMedia');
    $instanceId = spl_object_hash($instance);
    $this->assertInstanceOf('CommonSenseApiMedia', $instance);

    // Create multiple instances and check if it's the same one.
    for ($i = 0; $i < 10; $i++) {
      $instanceName = 'instance' . $i;
      $$instanceName = CommonSenseApi::instance($this->api, 'CommonSenseApiMedia');
      $this->assertInstanceOf('CommonSenseApiMedia', $$instanceName);

      $instanceIdName = 'instanceId' . $i;
      $$instanceIdName = spl_object_hash($$instanceName);
      $this->assertEquals($instanceId, $$instanceIdName);
    }
  }

  /**
   * Tests for __construct().
   */
  public function testInit()
  {
    // Check the mode settings.
    $this->api = new CommonSenseApi($this->client_id, $this->app_id);
    $this->assertEquals($this->api->host, 'https://api.commonsense.org');

    $this->api = new CommonSenseApi($this->client_id, $this->app_id, TRUE);
    $this->assertEquals($this->api->host, 'https://api-dev.commonsense.org');
  }

  /**
   * Tests for request().
   */
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

  /**
   * Tests for education().
   */
  public function testEducation()
  {
    // Check the class instance.
    $instace = $this->api->education();
    $this->assertTrue($instace instanceof CommonSenseApiEducation);
  }

  /**
   * Tests for media().
   */
  public function testMedia()
  {
    // Check the class instance.
    $instace = $this->api->media();
    $this->assertTrue($instace instanceof CommonSenseApiMedia);
  }

  /**
   * Tests for get_list().
   */
  public function testGetItem()
  {
    $id = 1247882;  // Minecraft
    $response = $this->api->education()->get_item('products', $id);
    $product = $response->response;

    $this->assertEquals($response->statusCode, 200);
    $this->assertInstanceOf('StdClass', $product);
    $this->assertTrue(is_int($product->id));
    $this->assertNotEmpty($product->title);
  }

  /**
   * Tests for get_list().
   */
  public function testGetList()
  {
    $response = $this->api->education()->get_list('products');
    $this->assertEquals($response->statusCode, 200);
    $this->assertGreaterThan(0, $response->count);
    $this->assertGreaterThan(0, count($response->response));

    $products = $response->response;
    foreach ($products as $product) {
      $this->assertTrue(is_int($product->id));
      $this->assertNotEmpty($product->title);
    }
  }
}
