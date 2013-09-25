SphinxSearchBundle
==================

With this bundle you can use Sphinx to search in your Symfony2 project.

Configuration
-------------
Default configuration must work, but you can set connect parameters manual by include a *sphinxsearch* section to your config.yml
``` yaml
sphinxsearch:
    searchd:
        host:   localhost
        port:   9312
```

**host** and **port** - is a parameters to connect to your Sphinx daemon.

If you want to connect to a socket, please change config to:
``` yaml
sphinxsearch:
    searchd:
        socket:   /path/to/socket.file
```

Known exceptions
----------------
- **EmptyIndexException** - you will see this exception if try to search without indexes.
- **NoSphinxAPIException** - this exception throws if not SphinxAPI was found.

Example - simple
----------------

To use search in a controller:
``` php
public function searchAction(Request $request)
{
    $searchd = $this->get('iakumai.sphinxsearch.search');
    return $sphinxSearch->search($request->query->get('q', ''), array('IndexName'));
}
```

This code will use IndexName index to search for a query in *q*-get parameter;

Example - hard
--------------
You can use all methods, that provides by [PHP SphinxAPI](https://github.com/romainneutron/Sphinx-Search-API-PHP-Client).

For example:
``` php
public function searchAction(Request $request)
{
    $searchd = $this->get('iakumai.sphinxsearch.search');
    $searchd->setLimits(0, 100);
    return $sphinxSearch->search($request->query->get('q', ''), array('IndexName'));
}
```
