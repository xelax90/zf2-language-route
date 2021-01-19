<?php
namespace ZF2LanguageRoute;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\EventManager\EventInterface;
use Zend\Mvc\MvcEvent;
use Zend\Router\Http\TreeRouteStack as ZFTreeRouteStack;
use Laminas\Router\Http\TreeRouteStack as LaminasTreeRouteStack;

class Module implements ConfigProviderInterface, ServiceProviderInterface, BootstrapListenerInterface, ViewHelperProviderInterface{
	
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
				Listener\RouteListener::class => Listener\Factory\RouteListenerFactory::class,
				Options\LanguageRouteOptions::class => Options\Factory\LanguageRouteOptionsFactory::class
			],
			'delegators' => [
				'HttpRouter' => [ Mvc\Router\Http\Factory\LanguageTreeRouteStackDelegatorFactory::class ],
				ZFTreeRouteStack::class => [ Mvc\Router\Http\Factory\LanguageTreeRouteStackDelegatorFactory::class ],
				LaminasTreeRouteStack::class => [ Mvc\Router\Http\Factory\LanguageTreeRouteStackDelegatorFactory::class ],
			]
		];
	}

	public function getViewHelperConfig() {
		return [
			'factories' => [
				View\Helper\LanguageSwitch::class => View\Helper\Factory\LanguageSwitchFactory::class
			],
			'aliases' => [
				'languageSwitch' => View\Helper\LanguageSwitch::class
			]
		];
	}

}