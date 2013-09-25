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
```

Services
--------
- **@iakumai.sphinxsearch.search** - base search engine to use Sphinx search.

Maybe you want to use another class in **@iakumai.sphinxsearch.search** service. To do this put a full class name to the parameter named **%iakumai.sphinxsearch.search.class%**.

Exceptions
----------------
- **EmptyIndexException** - you will see this exception if try to search without indexes.
- **NoSphinxAPIException** - this exception throws if not SphinxAPI was found.


Examples
----------------

This code will use IndexName index to search for a query in *q*-get parameter:
```php
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
