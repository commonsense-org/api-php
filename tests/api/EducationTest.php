<?php

require dirname(__FILE__) . '/../BaseTest.php';
require dirname(__FILE__) . '/../../source/Api.php';

class CommonSenseApiEducationTest extends CommonSenseApiBaseTest
{
  public function setUp()
  {
    parent::setUp();
    $this->education = $this->api->education();
  }

  /**
   * Tests for products().
   */
  public function testProducts()
  {
    // $response = $this->education->products();
    // $this->assertEquals($response->statusCode, 200);
    // $this->assertGreaterThan(0, $response->count);
  }
}
