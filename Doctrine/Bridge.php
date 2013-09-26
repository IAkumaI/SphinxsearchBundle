<?php

namespace IAkumaI\SphinxsearchBundle\Doctrine;

use Symfony\Component\DependencyInjection\Container;
use Docrtine\ORM\EntityManager;


/**
 * Bridge to find entities for search results
 */
class Bridge implements BridgeInterface
{
    /**
     * Symfony2 container
     * @var Container
     */
    protected $container;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * EntityManager name
     * @var string
     */
    protected $emName;

    /**
     * Indexes list
     * Key is index name
     * Value is entity name
     *
     * @var array
     */
    protected $indexes = array();

    /**
     * Constructor
     *
     * @param Container $container Symfony2 DI-container
     * @param string[optional] $emName EntityManager name
     * @param array[optional] $indexes List of search indexes with entity names
     */
    public function __construct(Container $container, $emName = 'default', $indexes = array())
    {
        $this->container = $container;
        $this->emName = $emName;
        $this->setIndexes($indexes);
    }

    /**
     * Get an EntityManager
     * @return EntityManager
     */
    public function getEntityManager()
    {
        if ($this->em === null) {
            $this->setEntityManager($this->container->get('doctrine')->getEntityManager($this->emName));
        }

        return $this->em;
    }

    /**
     * Set an EntityManager
     *
     * @param EntityManager $em
     *
     * @throws LogicException If entity manager already set
     */
    public function setEntityManager(EntityManager $em)
    {
        if ($this->em !== null) {
            throw new \LogicException('Entity manager can only be set before any results are fetched');
        }

        $this->em = $em;
    }

    /**
     * Set indexes list
     *
     * @param array $indexes
     */
    public function setIndexes(array $indexes)
    {
        $this->indexes = $indexes;
    }

    /**
     * Add entity list to sphinx search results
     * @param  array  $results Sphinx search results
     * @param  string $index   Index name
     *
     * @return array
     *
     * @throws LogicException If results come with error
     * @throws InvalidArgumentException If index name is not configured
     */
    public function parseResults(array $results, $index)
    {
        if (!empty($results['error'])) {
            throw new \LogicException('Search completed with errors');
        }

        if (!isset($this->indexes[$index])) {
            throw new \InvalidArgumentException('Unknown index name');
        }

        if (empty($results['matches'])) {
            return $results;
        }

        foreach ($results['matches'] as $id => &$match) {
            $match['entity'] = $this->getEntityManager()->getRepository($this->indexes[$index])->find($id);
        }

        return $results;
    }
}
