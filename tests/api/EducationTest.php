<?php

require_once dirname(__FILE__) . '/../BaseTest.php';
require_once dirname(__FILE__) . '/../../source/Api.php';

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
  public function testContentTypesItem()
  {
    $this->contentTypeListTest('products');
    $this->contentTypeListTest('blogs');
    $this->contentTypeListTest('app_flows');
    $this->contentTypeListTest('lists');
    $this->contentTypeListTest('user_reviews');
    $this->contentTypeListTest('boards');
  }

  /**
   * Simple tests run on each content type supported by the API item() call.
   */
  public function testContentTypesLists()
  {
    $this->contentTypeItemTest('products');
    $this->contentTypeItemTest('blogs');
    $this->contentTypeItemTest('app_flows');
    $this->contentTypeItemTest('lists');
    $this->contentTypeItemTest('user_reviews');
    $this->contentTypeItemTest('boards');
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
    // Call the list() API.
    $functionName = 'get_' . $contentType . '_list';
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
   * Runs simple tests on a content type retrieved from the
   * API item() calls.
   *
   * @param string
   *   the content type to be tested.
   */
  protected function contentTypeItemTest($contentType)
  {
    // Get random ID for the content type that was stored in contentTypeListTest().
    $id_index = array_rand(self::$ids[$contentType]);
    $id = self::$ids[$contentType][$id_index];

    // Call the item() API.
    $functionName = 'get_' . $contentType . '_item';
    $response = $this->education->{$functionName}($id);
    $item = $response->response;

    $this->assertEquals($response->statusCode, 200);

    // Perform various tests.
    $this->assertTrue(is_int($item->id));

    // Check for valid product types.
    $this->assertContains($item->type, $this->contentTypeMap[$contentType]);
  }

  /**
   * Tests for get_products_item().
   */
  public function testProduct()
  {
    // Get a random product from the ID list.
    $random_key = array_rand(self::$ids['products']);
    $id = self::$ids['products'][$random_key];

    // Get the product.
    $response = $this->education->get_products_item($id);
    $this->assertEquals($response->statusCode, 200);
    $this->assertInstanceOf('StdClass', $response->response);
  }

  /**
   * Test for getting a list of taxonomy terms of a given vocabulary.
   */
  public function testGetTermsList()
  {
    $vocabularies = array(
      'app_genre',
      'app_platforms',
      'app_publishers',
      'entertainment_product_awards',
      'pricing_structure',
      'education_special_needs',
      'grades',
      'education_subjects',
      'education_skills',
      'tv_genre',
    );

    foreach ($vocabularies as $vocabulary) {
      $response = $this->education->get_terms_list($vocabulary);

      $terms = $response->response;
      foreach ($terms as $term) {
        $this->assertNotNull($term->parent_id);
        $this->assertNotNull($term->type);
        $this->assertNotNull($term->id);
        $this->assertEquals($term->type, $vocabulary);
      }
    }
  }
}
