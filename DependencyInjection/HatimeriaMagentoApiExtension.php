<?php
namespace Hatimeria\MagentoApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor,
    Symfony\Component\HttpKernel\DependencyInjection\Extension,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\Config\FileLocator;

class HatimeriaMagentoApiExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor     = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        foreach (array('services') as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        $apiDefinition = $container->getDefinition('hatimeria_magento_api.api');

        $apiDefinition->replaceArgument(0, $config['host']);
        $apiDefinition->replaceArgument(1, $config['user']);
        $apiDefinition->replaceArgument(2, $config['key']);
        $apiDefinition->replaceArgument(3, $config['route']);
        $apiDefinition->replaceArgument(4, $config['defaults']);
    }

}