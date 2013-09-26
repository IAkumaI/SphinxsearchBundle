SphinxsearchBundle
==================

With this bundle you can use Sphinx to search in your Symfony2 project.

Installation
------------

### Step 1: Download SphinxsearchBundle using composer

In your composer.json, add SphinxsearchBundle:

```js
{
    "require": {
        "iakumai/sphinxsearch-bundle": "dev-master"
    }
}
```

Now, you must update your vendors using this command :

```bash
$ php composer.phar update iakumai/sphinxsearch-bundle
```

### Step 2: Add bundle in AppKernel.php
``` php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new IAkumaI\SphinxsearchBundle\SphinxsearchBundle()
    );
}
```

### Step 3: Configure your config.yml
By default bundle does not need to be a configured, but has some options for you.

**Full configuration :**
``` yml
# app/config/config.yml
sphinxsearch:
    searchd:
        # Host name for your Sphinx daemon
        host: localhost
        # Port number for your Sphinx daemon
        port: 9312
        # If you want to connect via scoket
        socket: /path/to/socket.file
    indexes:
        # List of sphinx index names (key) and entity names (value)
        # to use it in searchEx() method
        IndexName: "Bundle:Entity"
```

Services
--------
- **@iakumai.sphinxsearch.search** - base search engine to use Sphinx search.

Maybe you want to use another class in **@iakumai.sphinxsearch.search** service. To do this put a full class name to the parameter named **%iakumai.sphinxsearch.search.class%**.

- **@iakumai.sphinxsearch.doctrine.bridge** - bridge to dictrine datebase.

Maybe you want to use another class in **@iakumai.sphinxsearch.doctrine.bridge** service. To do this put a full class name to the parameter named **%iakumai.sphinxsearch.doctrine.bridge.class%**. It must implements a `IAkumaI\SphinxsearchBundle\Doctrine\BridgeInterface`

Exceptions
----------
- **EmptyIndexException** - you will see this exception if try to search without indexes.
- **NoSphinxAPIException** - this exception throws if not SphinxAPI was found.

Examples
--------

This code will use IndexName index to search for a query in *q*-get parameter:
``` php
// In a controller
public function searchAction(Request $request)
{
    $searchd = $this->get('iakumai.sphinxsearch.search');
    return $sphinxSearch->search($request->query->get('q', ''), array('IndexName'));
}
```

You can use all methods, that provides by [PHP SphinxAPI](https://github.com/romainneutron/Sphinx-Search-API-PHP-Client).

For example:
```php
// In a controller
public function searchAction(Request $request)
{
    $searchd = $this->get('iakumai.sphinxsearch.search');
    $searchd->setLimits(0, 100);
    return $sphinxSearch->search($request->query->get('q', ''), array('IndexName'));
}
```

Now bundle can auto convert search results to entities if you will search for one index or define a *index_name* attribute in sphinx config.
To to this, first configure index names, for example:
``` yml
# app/config/config.yml
sphinxsearch:
    indexes:
        IndexName: "Bundle:Entity"
```

To convert multiple queries please add *index_name* attribute to your sphinx.conf file, for example:
```
source Example
{
    sql_query = SELECT id, ...., 'IndexName' as 'index_name' FROM my_table
    sql_attr_string = index_name
}

index IndexName
{
    source = Example
    path = /your/own/path
}
```

Now you can execute searchEx() method:
``` php
// In a controller
public function searchAction(Request $request)
{
    $searchd = $this->get('iakumai.sphinxsearch.search');
    $results_one = $sphinxSearch->searchEx($request->query->get('q', ''), 'IndexName');
    // or for multiple indexes (index_name attribute must exists)
    $results_two = $sphinxSearch->searchEx($request->query->get('q', ''), array('IndexName', 'SeconIndexName'));
}
```

**$results_one** now will contains something like this:
```
array(10) {
  .....
  ["matches"]=>
  array(20) {
    [22]=>
    array(3) {
      ["weight"]=>
      string(1) "2"
      ["attrs"]=>
      array(0) {
      }
      ["entity"]=> ... // Here is your Bundle:Entity
    }
    .........
```

**$results_two** now will contains something like this:
```
array(10) {
  .....
  ["matches"]=>
  array(20) {
    [22]=>
    array(3) {
      ["weight"]=>
      string(1) "2"
      ["attrs"]=>
      array(0) {
        ["index_name"]=>
        string(9) "IndexName"
      }
      ["entity"]=> ... // Here is your Bundle:Entity
    }
    .........
```
