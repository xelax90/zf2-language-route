<?php

/*
 * Copyright (C) 2016 schurix
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

namespace ZF2LanguageRoute\Listener\Factory;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use ZF2LanguageRoute\Options\LanguageRouteOptions;

/**
 * Creates RouteListener instance
 *
 * @author schurix
 */
class RouteListenerFactory implements FactoryInterface{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
		$languageOptions = $container->get(LanguageRouteOptions::class);
        $router = $container->get('router');
        $request = $container->get('request');
		$translator = $container->get('MvcTranslator');
		$authService = null;
		$userMapper = null;
		if($container->has('zfcuser_auth_service')){
			$authService = $container->get('zfcuser_auth_service');
		}
		if($container->has('zfcuser_user_mapper')){
			$userMapper = $container->get('zfcuser_user_mapper');
		}
		
		return new $requestedName($languageOptions, $router, $request, $translator, $authService, $userMapper);
	}
}
