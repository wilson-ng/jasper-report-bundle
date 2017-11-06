<?php
declare(strict_types=1);

/**
 * This file is part of the JasperReportBundle.
 *
 * (c) Wilson Ng <frozalid.wilson@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WilsonNg\JasperReportBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * The extension of the bundle.
 * 
 * @author Wilson Ng <frozalid.wilson@gmail.com>
 */
class WilsonNgJasperReportExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $this->_registerClientConfig($container, $config);
    }

    private function _registerClientConfig(ContainerBuilder $container, array $config)
    {
        if (isset($config['server'])) {
            $server = $config['server'];

            $jasperServerDefinition = $container->getDefinition('wilson_ng_jasper_report.service.jasper_server');
            $jasperServerDefinition->addMethodCall('setScheme', [$server['scheme']]);
            $jasperServerDefinition->addMethodCall('setHost', [$server['host']]);
            $jasperServerDefinition->addMethodCall('setBaseUrl', [$server['base_url']]);
            $jasperServerDefinition->addMethodCall('setPort', [$server['port']]);
            $jasperServerDefinition->addMethodCall('setUsername', [$server['username']]);
            $jasperServerDefinition->addMethodCall('setPassword', [$server['password']]);
            $jasperServerDefinition->addMethodCall('setTimeout', [$server['timeout']]);
            $jasperServerDefinition->addMethodCall('setOrganization', [$server['organization']]);
        }
    }
}
