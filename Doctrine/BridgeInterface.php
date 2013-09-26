<?php

namespace IAkumaI\SphinxsearchBundle\Doctrine;


/**
 * Doctrine bridge interface
 */
interface BridgeInterface
{
    /**
     * Add entity list to sphinx search results
     * @param  array  $results Sphinx search results
     * @param  string $index   Index name
     * @return array
     */
    public function parseResults(array $results, $index);
}
