<?php

namespace ZF2LanguageRoute\Mvc\Router\Http\Factory;

use Zend\ServiceManager\Factory\DelegatorFactoryInterface;
use Interop\Container\ContainerInterface;
use ZF2LanguageRoute\Mvc\Router\Http\LanguageTreeRouteStack;
use ZF2LanguageRoute\Options\LanguageRouteOptions;

/**
 * Description of LanguageTreeRouteStackDelegatorFactory
 *
 * @author schurix
 */
class LanguageTreeRouteStackDelegatorFactory implements DelegatorFactoryInterface{
	public function __invoke(ContainerInterface $container, $name, callable $callback, array $options = null) {
		$router = $callback();
		
		if(!$router instanceof LanguageTreeRouteStack){
			return $router;
		}
		
		if($container->has(LanguageRouteOptions::class)){
			$router->setLanguageOptions($container->get(LanguageRouteOptions::class));
		}
		
		if($container->has('zfcuser_auth_service')){
			$router->setAuthenticationService($container->get('zfcuser_auth_service'));
		}
		
		return $router;
	}
}
