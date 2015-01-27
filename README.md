Common Sense API PHP Driver
===========================

PHP driver to connect to the Common Sense REST API.  Direct calls to the REST API are abstracted out by the driver.  The REST API documentation can be found at: [https://github.com/commonsense-org/api-documentation](https://github.com/commonsense-org/api-documentation)

## Getting Started

<pre>
// Instantiate the main API object.
$api = new CommonSenseApi($client_id, $app_id);

// Work with the Education platform API.
$education = $api->education();
$response = $education->get_products_list();

// The response is the JSON output from the API (see API documentation for details).
// If all goes well, a 200 response.
echo $response->statusCode;

// For list calls, the total record count.
echo $response->count;

// The data set.  In this case, an array of product data objects.
$products = $response->response;

foreach($products as $product) {
	echo $product->id;  // The ID of the product.
	echo $product->title;  // The title of the product.
}

// Calls to a platform can be chained.
$response = $api->education()->get_products_list();
</pre>

CommonSenseApi
==============

## new CommonSenseApi(client_id, app_id)

The `CommonSenseApi` object is the main application container. The object manages all REST calls to Media and Education plaform API's.

#### Arguments
* `client_id` string - A valid client ID assigned by Common Sense.
* `app_id` string - A valid application ID assigned by Common Sense.

#### Return
* an instance of a `CommonSenseApi` object.

## #education()

Retrieves an instance of the `CommonSenseApiEducation` object which is used to fetch content data from the [Graphite website](https://www.graphite.org).

#### Return
* An instance of a `CommonSenseApiEducation` object.

## #media()

Retrieves an instance of the `CommonSenseApiMedia` object which is used to fetch content data from [consumer media website](https://www.commonsensemedia.org).

#### Return
* An instance of a `CommonSenseApiMedia` object.

* * *


CommonSenseApiEducation
=======================

The Education platform calls fetch content data from the [Graphite website](https://www.graphite.org).

## #get_products_list([options])

Get a list of Graphite rated products, which includes meta-data of each product along with the Graphite review.

#### Arguments

* `options` object - Filter/sort option with the following keys:
	* `page` integer - The page offset of the data set.
		* default: 1
	* `limit` integer - The number of records to return per page.
		* default: 10
	* `fields` array - A list of data fields to be returned.
		* default: *all data fields*

## #get_products_item(id, [options])

Get a Graphite rated product, which includes meta-data of the product along with the Graphite review.

#### Arguments

* `id` integer - The system ID of a product.
	* default: 1
* `options` object - Filter/sort option with the following keys:
	* `fields` array - A list of data fields to be returned.
		* default: *all data fields*

## #get_user_reviews_list([options])

Get a list of Graphite product user reviews (Field Notes).

#### Arguments

* `options` object - Filter/sort option with the following keys:
	* `page` integer - The page offset of the data set.
		* default: 1
	* `limit` integer - The number of records to return per page.
		* default: 10
	* `fields` array - A list of data fields to be returned.
		* default: *all data fields*

## #get_user_reviews_item(id, [options])

Get a Graphite product user reviews (Field Notes).

#### Arguments

* `id` integer - The system ID of a user review.
	* default: 1
* `options` object - Filter/sort option with the following keys:
	* `fields` array - A list of data fields to be returned.
		* default: *all data fields*

## #get_app_flows_list([options])

Get a list of user App Flow lesson plans.

#### Arguments

* `options` object - Filter/sort option with the following keys:
	* `page` integer - The page offset of the data set.
		* default: 1
	* `limit` integer - The number of records to return per page.
		* default: 10
	* `fields` array - A list of data fields to be returned.
		* default: *all data fields*

## #get_app_flows_item(id, [options])

Get a user App Flow lesson plan.

#### Arguments

* `id` integer - The system ID of an App Flow.
	* default: 1
* `options` object - Filter/sort option with the following keys:
	* `fields` array - A list of data fields to be returned.
		* default: *all data fields*


## #get_lists_list([options])

Get a list of Graphite's editorial team's top pick lists.

#### Arguments

* `options` object - Filter/sort option with the following keys:
	* `page` integer - The page offset of the data set.
		* default: 1
	* `limit` integer - The number of records to return per page.
		* default: 10
	* `fields` array - A list of data fields to be returned.
		* default: *all data fields*

## #get_lists_item(id, [options])

Get a Graphite editorial team's top pick list.

#### Arguments

* `id` integer - The system ID of a top pick list.
	* default: 1
* `options` object - Filter/sort option with the following keys:
	* `fields` array - A list of data fields to be returned.
		* default: *all data fields*

## #get_boards_list([options])

Get a list of user Boards.

#### Arguments

* `options` object - Filter/sort option with the following keys:
	* `page` integer - The page offset of the data set.
		* default: 1
	* `limit` integer - The number of records to return per page.
		* default: 10
	* `fields` array - A list of data fields to be returned.
		* default: *all data fields*

## #get_boards_item(id, [options])

Get a user Board.

#### Arguments

* `id` integer - The system ID of a board.
	* default: 1
* `options` object - Filter/sort option with the following keys:
	* `fields` array - A list of data fields to be returned.
		* default: *all data fields*


## #get_blogs_list([options])

Get a list of Graphite's editorial team's blog posts.

#### Arguments

* `options` object - Filter/sort option with the following keys:
	* `page` integer - The page offset of the data set.
		* default: 1
	* `limit` integer - The number of records to return per page.
		* default: 10
	* `fields` array - A list of data fields to be returned.
		* default: *all data fields*

## #get_blogs_item(id, [options])

Get a Graphite editorial team's blog post.

#### Arguments

* `id` integer - The system ID of a blog post.
	* default: 1
* `options` object - Filter/sort option with the following keys:
	* `fields` array - A list of data fields to be returned.
		* default: *all data fields*

* * *

CommonSenseApiMedia
===================

The Media platform calls fetch content data from the [Consumer Media website](https://www.commonsensemedia.org).