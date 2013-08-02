# MeuMobi: API Tech Specs

## Making API Calls

The API uses REST to perform actions on resources. Resources are represented by URL paths. Actions can take parameters that are passed as query strings (?param=value) (no named params anymore since we are moving to REST, and it mandates a single URI for each resource).

The API is located at [http://enterprise.meumobilesite.com](http://enterprise.meumobilesite.com).

A simple API request would look like this: GET http://enterprise.meumobilesite.com/api/{domain}/categories

## Authentication

Read calls do not need authentication. You need to authenticate only when you want to add, edit or delete a resource. Authentication can be sending a custom header. The authentication method we chose is a token unique to every user.

### HTTP Header

The header should be called X-Authentication-Token and should contain your user's auth token.

## Cache Management

The template engine should cache every request made to the API to lower server usage. This cache can be stored indefinitely or cleared from time to time to save disk space. Every use of the cached data should be validated with the API by using the ETags sent with the response.

### How It Works

When a URL is retrieved, the API will return the response along with its response headers. One of this headers is the ETag, containing the ETag value of the resource. This value is a checksum of the resource, meaning that if the resource doesn't change the ETag value will remain the same. One example of header would be ETag: "d057d85fc18d4dd0524ab4c6e7844c3bf1a27df1".

Later, when the Template Engine needs to use the same resource again, it should validate the resource to ensure it wasn't changed. This validation is made by requesting the same resource again, and sending a "If-None-Match" header, along with the known ETag value of the resource. For example, If-None-Match: "d057d85fc18d4dd0524ab4c6e7844c3bf1a27df1".

If the resource hasn't changed, the API will return an empty response and a "304 Not Modified" HTTP status code. It means that the cached version is still good and shold be used. If the resource is now different, the API will return the full resource in the response and a new ETag value for further caching.

## API Responses

The API responses will always be in JSON format with "application/json" Content-Type, unless other format is requested through an extension. If the request is successful, it will return the description of the resource as describe in every method. If there are any errors with the request, it will return a JSON with a message explaining the error, like this:

    {
        "error": "Authorization token not provided"
    }

## API Endpoints Reference

### Site

#### GET /{domain}

Gets info of the current site.

Example response:

	{
	   "sites":{
	      "id":"1",
	      "category_id":"1",
	      "segment":"restaurant",
	      "theme":"boteco",
	      "skin":"ae3232",
	      "slug":"juliogreff",
	      "title":"Julio Greff",
	      "description":"Julio Greff",
	      "timetable":"",
	      "street":"",
	      "number":"",
	      "zip":"",
	      "complement":"",
	      "zone":"",
	      "city":"",
	      "state":"",
	      "country":"BR",
	      "email":"",
	      "phone":"",
	      "website":"",
	      "facebook":"",
	      "twitter":"",
	      "latitude":null,
	      "longitude":null,
	      "created":"2011-01-26 07:34:55",
	      "modified":"2011-01-26 07:34:55",
	      "logo":null
	   }
	}

#### GET /{domain}/performance

Combines all the needed requests for a front page.

    { "business" : { "address" : null,
          "email" : "",
          "facebook" : "http://www.facebook.com/username/",
	      "phone" : "",
	      "timetable" : "",
	      "twitter" : "",
	      "website" : ""
	    },
	  "categories" : [ { "created_at" : "2013-02-27 18:13:53",
		"extensions" : [  ],
		"id" : "2",
		"title" : "Main",
		"type" : "articles",
		"updated_at" : "2013-02-27 18:13:53"
	      },
	      { "created_at" : "2013-03-05 10:47:15",
		"extensions" : [ { "created_at" : "2013-03-06 17:35:49",
		      "extension" : "rss",
		      "id" : "5137a8a53f482ec7ba000000",
		      "updated_at" : "2013-03-06 17:35:54",
		      "url" : "http://feeds.feedburner.com/Mobilecrunch"
		    } ],
		"id" : "4",
		"title" : "My Category",
		"type" : "articles",
		"updated_at" : "2013-03-06 17:35:54"
	      }
	    ],
	  "news" : [ { "author" : "Natasha Lomas",
		"created_at" : "2013-02-28 20:52:09",
		"description" : "<p>Japanese electronics ...</p>",
		"id" : "512feda93f482e14e1000000",
		"images" : [ { "description" : null,
		      "id" : "890",
		      "path" : "uploads/items/890.jpg",
		      "title" : ""
		    } ],
		"published_at" : "2013-02-28 22:07:00",
		"title" : "Fujitsu’s Senior-Focused Smartphone Is A Thoughtful Use Of Android That Tucks Away Complexity",
		"updated_at" : "2013-02-28 20:52:11"
	      } ],
	  "site" : { "created_at" : "2013-02-27 18:13:04",
	      "date_format" : null,
	      "description" : "",
	      "id" : "1",
	      "logo" : null,
	      "photos" : [  ],
	      "segment" : "meumobi",
	      "skin" : "ae3232",
	      "theme" : "4e52d2738062333444000002",
	      "timezone" : "America/Sao_Paulo",
	      "title" : "Ipanemax",
	      "updated_at" : "2013-03-06 17:35:54",
	      "webputty_token" : null
	    }
	}

#### POST /{domain}/mail

Sends a mail to the site's contact mail.

Parameters:

* name
* mail
* phone
* message
 
Authentication Token: X-Authentication-Token: 9456bbf53af6fdf30a5d625ebf155b4018c8b0aephp

### News Feed

#### GET /{domain}/news/category

Get news feed category

Example response:

	{
		"id": "962",
		"site_id": "215",
		"parent_id": null,
		"type": "articles",
		"title": "News",
		"feed_url": null,
		"visibility": "-1",
		"populate": "auto",
		"icon": null,
		"order": "1",
		"created": "2013-03-14 15:02:08",
		"modified": "2013-06-27 21:19:52",
		"updated": "2013-06-27 21:19:52",
		"items_count": 50
	}

#### GET /{domain}/news

Gets a list of non-categorized articles.

Example response:

	{
	   "articles":[
	      {
		 "feed_id":"3",
		 "guid":"http:\/\/www.rj.gov.br\/web\/guest\/exibeconteudo?articleId=451045",
		 "link":"http:\/\/www.rj.gov.br\/web\/guest\/exibeconteudo?articleId=451045",
		 "pubdate":"2011-05-08 18:41:00",
		 "format":"html",
		 "title":"Fam\u00edlias comemoram Dia das M\u00e3es em concerto no Theatro Municipal",
		 "description":"Lorem Ipsum Dolor Sit Amet",
		 "author":"Renata Oliveira",
		 "id":"1",
		 "site_id":"2",
		 "parent_id":"0",
		 "type":"articles",
		 "order":"0",
		 "created":"2011-05-08 21:33:03",
		 "modified":"2011-05-08 21:33:03",
		 "images":[
		 ]
	      }
	   ]
	}

### Categories

#### GET /{domain}/categories

Gets a list of categories.

Params:

- **visibility**: whether the categories are visible or not. Possible values: 1 (visible categories), 0 (invisible categories), all (all categories). Only visible categories is the default.

Example response:

	{
	   "categories":[
	      {
		 "id":"1",
		 "parent_id":"0",
		 "title":"Cardapio",
		 "order":"0",
		 "created":"2011-01-26 07:34:55",
		 "modified":"2011-01-26 07:34:55"
		 "extensions": {
		 "extension": "store-locator", 
		 "itemLimit": 10,
		 "language": "en"
	      },
	      {...}    
	    ]
	}

#### POST /{domain}/categories

Posts a new category.

Parameters:
- **parent_id**: parent category
- **title**: category title
- **feed_url**: feed for the category

#### GET /{domain}/categories/{id}

Gets a category.

Example response:

	{
	   "categories":{
	      "id":"1",
	      "site_id":"1",
	      "parent_id":"0",
	      "title":"Cardapio",
	      "order":"0",
	      "created":"2011-01-26 07:34:55",
	      "modified":"2011-01-26 07:34:55"
	   }
	}

#### DELETE /{domain}/categories/{id}

Deletes a category

#### GET /{domain}/categories/{id}/children

Gets a list of all categories with parent_id = {id}

Parameters:

- **depth**: depth of nested categories to return. If > 0, returns the children of the children, if any. Default is 0.

Example response:

    {
	   "categories":[
	      {
		 "id":"1",
		 "parent_id":"0",
		 "title":"Cardapio",
		 "order":"0",
		 "created":"2011-01-26 07:34:55",
		 "modified":"2011-01-26 07:34:55"
	      },
	      {
		  ...
	      }
	   ]
	}

### Items

#### GET /{domain}/categories/{category_id}/items

Gets a list of items.

Example response:

    {
	   "items":[
	      {
		 "title":"Teste",
		 "price":"5,00",
		 "description":"500",
		 "featured":"",
		 "id":"6",
		 "site_id":"1",
		 "category_id":"0",
		 "feed_id" :,"";
		  "type":"product",
		 "order":"0",
		 "created":"2011-01-26 23:23:22",
		 "modified":"2011-01-26 23:23:22"
	      },
	      {
		 ...
	      }
	   ]
	}

#### GET /{domain}/items/{id} 

Gets an item.

Example response:

    {
	   "articles":{
	      "id":"1",
	      "feed_id":"1",
	      "guid":"http:\/\/lifetasteslikefood.wordpress.com\/?p=525",
	      "link":"http:\/\/lifetasteslikefood.wordpress.com\/2011\/01\/23\/my-soul-mate-food\/",
	      "title":"My Soul Mate, Food.",
	      "description":"Intimacy and close relationships are essential in our [...]",
	      "author":"lifetasteslikefood",
	      "pubdate":"2011-01-24 05:57:37",
	      "created":"2011-01-26 12:41:42"
	   }
	}

#### GET /{domain}/items/search

Returns a list of items as a result of the search, receives as parameter, fields and values used as search filter, on a pattern key/value,  only not accepts the field "site_id", this is configured by api

Parameters:

- **parent_id**: parent category id
- **title**: title of the item
- **description**: description of the item 
- **Any item field..**
 
Example of request:

http://meumobi.com/api/zbrahostel.int-meumobi.com/items/search?title=item&parent_id=233

Example response:

    {
	    "articles": [
		{
		    "_id": "515c77363f482e4e98000000", 
		    "author": "", 
		    "created": 1365014326, 
		    "description": "<p></p>", 
		    "guid": "", 
		    "images": [], 
		    "link": "", 
		    "modified": 1365014326, 
		    "order": 1, 
		    "parent_id": 9, 
		    "pubdate": 1365014326, 
		    "site_id": 1, 
		    "title": "My Item", 
		    "type": "articles"
		}, 
		{
		    "_id": "515e036e3f482eae12000000", 
		    "author": "", 
		    "created": 1365115758, 
		    "description": "<p></p>", 
		    "guid": "", 
		    "images": [], 
		    "link": "", 
		    "modified": 1365115758, 
		    "order": 1, 
		    "parent_id": 11, 
		    "pubdate": 1365115758, 
		    "site_id": 1, 
		    "title": "My Item", 
		    "type": "articles"
		}
	    ], 
	    "business": [
		{
		    "_id": "515c76d43f482e7398000000", 
		    "address": "Rua Cel. Pena de Moraes, 415 - Farroupilha - RS", 
		    "created": 1365014227, 
		    "description": "<p></p>", 
		    "geo": [
		        -51.3480038, 
		        -29.2284825
		    ], 
		    "images": [], 
		    "modified": 1365014227, 
		    "order": 2, 
		    "parent_id": 8, 
		    "phone": "", 
		    "site_id": 1, 
		    "title": "My Item", 
		    "type": "business"
		}
	    ]
	}

#### GET /{domain}/categories/{id}/search

Searches a list of items from a certain category.

Params:

- **keyword**: keywords to search in title and description.

#### POST /{domain}/items

Posts a new item.

Parameters:

Any item-specific param (like description, address, etc., can vary for each item), plus:

- **title**
- **parent_id**
- **type**
- **title**
- **related**: array of ids of related items. for example, in rest-client, ["4e9cb46e9a645d2277000000", "4e9cb46e9a645d2277000001", "4e9cb46e9a645d2277000002", ...]
 
#### POST /{domain}/items/{id}/add

Create an item as related to an existing.

Parameters:

Any item-specific param (like description, address, etc., can vary for each item), plus:

- **title**
- **parent_id**
- **type**

#### GET /{domain}/items/{id}/related

Gets the related items for this item.

#### GET /{domain}/items/{id}/images

Gets all the images from an item.

#### POST /{domain}/items/{id}/images

Adds an image to an item.

Parameters:

- **image**: image file (png, gif, jpeg)
- **visible**: visibility of the image (default is 0)
- **title**: title of the image
- **description**: description of the image
 
#### PUT /{domain}/items/{id}

Edits an item.

Parameters:

Any item-specific param (like description, address, etc., can vary for each item), plus:

- **title**
- **parent_id**
- **type**
- **title**
- **related**: array of ids of related items. for example, in rest-client, ["4e9cb46e9a645d2277000000", "4e9cb46e9a645d2277000001", "4e9cb46e9a645d2277000002", ...]
 
**Note**: when sending images to this action you should change PUT for POST, and send a X-HTTP-Method-Override: PUT header instead. This is a known bug and we're working on it.

Example request (in rest-client):

put "/ipanemax.com/items/21", {title: "New Title", description: "This is a new description"}

Example response:

    {
	   "articles":{
	      "id":"1",
	      "feed_id":"1",
	      "guid":"http:\/\/lifetasteslikefood.wordpress.com\/?p=525",
	      "link":"http:\/\/lifetasteslikefood.wordpress.com\/2011\/01\/23\/my-soul-mate-food\/",
	      "title":"My Soul Mate, Food.",
	      "description":"Intimacy and close relationships are essential in our [...]",
	      "author":"lifetasteslikefood",
	      "pubdate":"2011-01-24 05:57:37",
	      "created":"2011-01-26 12:41:42"
	   }
	}

#### DELETE /{domain}/items/{id}

Deletes an item.

#### GET /{domain}/items/by_category

Gets items grouped by their parent categories.

Parameters:

- **limit**: limit number of items to retrieve for each category.

Example response:

    { "2" : [  ],
	  "3" : [  ],
	  "5" : [  ],
	  "6" : [  ],
	  "7" : [ { "author" : "moi",
		"created" : "2011-05-09 04:37:38",
		"description" : "<p>teste</p>",
		"feed_id" : "",
		"format" : "bbcode",
		"guid" : "",
		"id" : "29",
		"images" : [],
		"link" : "",
		"modified" : "2011-05-09 04:37:38",
		"order" : "0",
		"parent_id" : "7",
		"pubdate" : "",
		"site_id" : "2",
		"title" : "un super article",
		"type" : "articles"
	      }
	    ],
	  "8" : [  ]
	}

#### GET /{domain}/items/latest

Gets the latest added items.

Parameters:

- **limit**: limit number of items to retrieve (default is 20)
- **parent_id**: filter by a certain category

Example response:

    [
	    {
		"_id": "511e891a3f482e52ec000013", 
		"author": "Matt Burns", 
		"created": 1360955674, 
		"description": "<p>With just a few quick steps, it?s easy to open the phone app on any locked iPhone running iOS 6.1...</p>", 
		"format": "html", 
		"guid": "http://techcrunch.com/?p=758053", 
		"link": "http://techcrunch.com/2013/02/14/new-ios-6-1-security-grants-limited-access-to-phone-app-photos-email-messages-facetime/", 
		"modified": 1360955674, 
		"order": 20, 
		"parent_id": 2, 
		"pubdate": 1360855859, 
		"site_id": 1, 
		"title": "New iOS 6.1 Security Flaw Grants Limited Access To Phone App, Photos, Email, Messages, FaceTime", 
		"type": "articles"
	    }
	    ...
	]

#### GET /{domain}/categories/{id}/promotions

Gets valid promotions.

Parameters:

- **time**: override time for the promotion. The default is the current time. Should be passed as UNIX seconds (returned by the time() function in PHP).
 
#### GET /{domain}/categories/{id}/geo/nearest

Gets the items near to a certain location.

Parameters:
- lat
- lng
 
#### GET /{domain}/categories/{id}/geo/inside

Gets the items inside a certain area.

Parameters:
- ne_lat
- ne_lng
- sw_lat
- sw_lng 

### Extensions

#### GET /{domain}/extensions

Gets extension's list

    [{ "_id": "4fd689f08ead0ea288000000",
	  "extension": "store-locator",
	  "category_id": 613,
	  "itemLimit": 10,
	  "language": "en"
	},
	...
	]

#### GET /{domain}/extensions/{id}

Gets extension's info

    { "_id": "4fd689f08ead0ea288000000",
	  "extension": "store-locator",
	  "category_id": 613,
	  "itemLimit": 10,
	  "language": "en"
	}

#### POST /{domain}/extensions

Creates a new extension

Required fields: extension, category_id, site_id, itemLimit, language

#### PUT /{domain}/extensions/{id}

Updates an extension

### Images

#### GET /{domain}/images/{id}

Shows an image.

#### POST /{domain}/images

Adds an image without an item.

Parameters:

- **visible**: visibility of the image
- **title**: title of the image 
- **description**: description of the image
 
#### PUT /{domain}/images/{id}

Edits an image

Parameters:

- **visible**: visibility of the image
- **title**: title of the image 
- **description**: description of the image

#### DELETE /{domain}/images/{id}

Deletes an image

## How to test API

### Suggested Tools

- add-on for firefox: https://addons.mozilla.org/en-US/firefox/addon/restclient/
- for a restclient to install from gems: bash$ gem install rest-client

### From restclient gem

Datas and ids are fake. Don't need to use Authentication for non PROD environment

    bash# restclient http://enterprise.meumobilesite.com/api
	irb(main):001:0> get '/hoggetcard.int-meumobi.com/categories', {"X-Authentication-Token" => "c8e75b59161a5922c04ede9a533867e371fa2933"}
    
	irb(main):001:0> post '/hoggetcard.int-meumobi.com/items', {parent_id: 9, title: 'Test', type: "articles"}, {"X-Authentication-Token" => "c8e75b59161a5922c04ede9a533867e371fa2933"}
    
	irb(main):003:0> put '/hoggetcard.int-meumobi.com/items/4e921c9096e4d21415000000', {title: 'Another Test'}, {"X-Authentication-Token" => "c8e75b59161a5922c04ede9a533867e371fa2933"}
    
	irb(main):003:0>  get '/hoggetcard.int-meumobi.com/categories', {"X-Authentication-Token" => "c8e75b59161a5922c04ede9a533867e371fa2933"}
	irb(main):003:0> delete '/hoggetcard.int-meumobi.com/items/4e921c9096e4d21415000000', {"X-Authentication-Token" => "c8e75b59161a5922c04ede9a533867e371fa2933"}
    
	irb(main):003:0> get '/hoggetcard.int-meumobi.com/categories/150/geo/nearest?lat=2.3934249&lng=48.8457628', {"X-Authentication-Token" => "c8e75b59161a5922c04ede9a533867e371fa2933"}
    
	irb(main):046:0> get '/hoggetcard.int-meumobi.com/items?type=restaurants', {"X-Authentication-Token" => "c8e75b59161a5922c04ede9a533867e371fa2933"} 
