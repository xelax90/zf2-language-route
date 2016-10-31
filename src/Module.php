<?php
namespace ZF2LanguageRoute;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\EventManager\EventInterface;
use Zend\Mvc\MvcEvent;

class Module implements ConfigProviderInterface, ServiceProviderInterface, BootstrapListenerInterface{
	
	public function onBootstrap(EventInterface $e) {
		if(!$e instanceof MvcEvent){
			return;
		}
		
		$app = $e->getApplication();
		$eventManager = $app->getEventManager();
		$container = $app->getServiceManager();
		
		/* @var $routeListener Listener\RouteListener */
		$routeListener = $container->get(Listener\RouteListener::class);
		$routeListener->attach($eventManager);
	}

	public function getConfig() {
		return include __DIR__ . '/../config/module.config.php';
	}

	public function getServiceConfig() {
		return [
			'factories' => [
				Listener\RouteListener::class => Listener\Factory\RouteListenerFactory::class
			]
		];
	}
}