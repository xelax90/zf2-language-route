<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ZF2LanguageRoute\View\Helper\Factory;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use ZF2LanguageRoute\Mvc\Router\Http\LanguageTreeRouteStack;
use ZF2LanguageRoute\Options\LanguageRouteOptions;

/**
 * Description of LanguageSwitchFactory
 *
 * @author schurix
 */
class LanguageSwitchFactory implements FactoryInterface{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
		if(!$container->has(LanguageRouteOptions::class)){
			return null;
		}
		
		$languageOptions = $container->get(LanguageRouteOptions::class);
		$routeMatch = $container->get('Application')->getMvcEvent()->getRouteMatch();
		return new $requestedName($languageOptions, $routeMatch);
	}
}
