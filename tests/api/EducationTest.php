<?php

require dirname(__FILE__) . '/../BaseTest.php';
require dirname(__FILE__) . '/../../source/Api.php';

/**
 * Tests for the Common Sense Education API endpoints.
 */
class CommonSenseApiEducationTest extends CommonSenseApiBaseTest
{
  // Store IDs from the list() calls to be used in the tests.
  public static $ids = array();

  // Map API content type path name to the data 'type' attribute.
  public $contentTypeMap = array(
    'products' => array('app', 'game', 'website'),
    'blogs' => array('blog'),
    'app_flows' => array('flow'),
    'lists' => array('top_picks'),
    'user_reviews' => array('field_note'),
    'boards' => array('board'),
  );

  public function setUp()
  {
    parent::setUp();
    $this->education = $this->api->education();
  }

  /**
   * Simple tests run on each content type supported by the API list() call.
   */
  public function testContentTypesLists()
  {
    $this->contentTypeListTest('products');
    $this->contentTypeListTest('blogs');
    $this->contentTypeListTest('app_flows');
    $this->contentTypeListTest('lists');
    $this->contentTypeListTest('user_reviews');
    $this->contentTypeListTest('boards');
  }

  /**
   * Runs simple tests on a content type retrieved from the
   * API list() calls.
   *
   * @param string
   *   the content type to be tested.
   */
  protected function contentTypeListTest($contentType)
  {
    $functionName = $contentType . '_list';
    $response = $this->education->{$functionName}();
    $items = $response->response;

    $this->assertEquals($response->statusCode, 200);
    $this->assertGreaterThan(0, $response->count);

    foreach ($items as $item) {
      // Store content type IDs to be used for testing.
      self::$ids[$contentType][] = $item->id;

      // Perform various tests.
      $this->assertTrue(is_int($item->id));

      // Check for valid product types.
      $this->assertContains($item->type, $this->contentTypeMap[$contentType]);
    }
  }

  /**
   * Tests for products_item().
   */
  public function testProduct()
  {
    // Get a random product from the ID list.
    $random_key = array_rand(self::$ids['products']);
    $id = self::$ids['products'][$random_key];

    // Get the product.
    $response = $this->education->products_item($id);
    $this->assertEquals($response->statusCode, 200);
    $this->assertInstanceOf('StdClass', $response->response);
  }
}
