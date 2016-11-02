<?php

namespace ZF2LanguageRoute\Options\Factory;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

/**
 * Description of LanguageRouteOptionsFactory
 *
 * @author schurix
 */
class LanguageRouteOptionsFactory implements FactoryInterface{
	const CONFIG_KEY = 'language-route';
	
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
		$config = $container->get('Config');
		return new $requestedName(isset($config[static::CONFIG_KEY]) ? $config[static::CONFIG_KEY] : []);
	}
}