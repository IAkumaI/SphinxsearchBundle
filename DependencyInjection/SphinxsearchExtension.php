<?php

namespace IAkumaI\SphinxsearchBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;


class SphinxsearchExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('parameters.yml');
        $loader->load('services.yml');

        if (isset($config['searchd'])) {
            $container->setParameter('iakumai.sphinxsearch.searchd.host', $config['searchd']['host']);
            $container->setParameter('iakumai.sphinxsearch.searchd.port', $config['searchd']['port']);
            $container->setParameter('iakumai.sphinxsearch.searchd.socket', $config['searchd']['socket']);
        }

        if (isset($config['sphinx_api'])) {
            $container->setParameter('iakumai.sphinxsearch.sphinx_api.file', $config['sphinx_api']['file']);
        }

        if (isset($config['indexes'])) {
            $container->setParameter('iakumai.sphinxsearch.indexes', $config['indexes']);
        }
    }

    public function getAlias()
    {
        return 'sphinxsearch';
    }
}
