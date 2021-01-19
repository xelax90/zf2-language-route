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

namespace ZF2LanguageRoute\Listener;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use ZF2LanguageRoute\Options\LanguageRouteOptions;
use Zend\Mvc\MvcEvent as ZFMvcEvent;
use Laminas\Mvc\MvcEvent as LaminasMvcEvent;
use Zend\Router\RouteStackInterface;
use Zend\Stdlib\RequestInterface;
use ZF2LanguageRoute\Mvc\Router\Http\LanguageTreeRouteStack;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\Authentication\AuthenticationServiceInterface;
use ZF2LanguageRoute\Entity\LocaleUserInterface;
use ZfcUser\Mapper\User as ZfcUserMapper;

/**
 * Injects language into translator and updates user locale
 *
 * @author schurix
 */
class RouteListener extends AbstractListenerAggregate{
	
	/** @var LanguageRouteOptions */
	protected $options;
	
	/** @var RouteStackInterface */
	protected $router;
	
	/** @var RequestInterface */
	protected $request;
	
	/** @var AuthenticationServiceInterface */
	protected $authService;
	
	/** @var ZfcUserMapper */
	protected $userMapper;
	
	 /** @var TranslatorInterface */
	protected $translator;
	
	function __construct(LanguageRouteOptions $options, RouteStackInterface $router, RequestInterface $request, TranslatorInterface $translator, AuthenticationServiceInterface $authService = null, ZfcUserMapper $userMapper = null) {
		$this->options = $options;
		$this->router = $router;
		$this->request = $request;
		$this->authService = $authService;
		$this->translator = $translator;
		$this->userMapper = $userMapper;
	}


	public function attach(EventManagerInterface $events, $priority = 10) {
        if (class_exists(ZFMvcEvent::class)) {
            $this->listeners[] = $events->attach(ZFMvcEvent::EVENT_ROUTE, [$this, 'onRoute'], $priority);
        }

        if (class_exists(LaminasMvcEvent::class)) {
            $this->listeners[] = $events->attach(LaminasMvcEvent::EVENT_ROUTE, [$this, 'onRoute'], $priority);
        }
	}

	public function onRoute($e){
		$router = $this->router;
		if(!$router instanceof LanguageTreeRouteStack){
			return;
		}
		$this->router->match($this->request);
		$locale = $this->router->getLastMatchedLocale();
		if(empty($locale)){
			return;
		}
		
		if(is_callable([$this->translator, 'setLocale'])){
			$this->translator->setLocale($locale);
		}
		
		if($this->authService && $this->authService->hasIdentity()){
			$user = $this->authService->getIdentity();
			if($user instanceof LocaleUserInterface){
				$user->setLocale($locale);
			}
			// use the zfc user mapper to update if available
			if($this->userMapper){
				$this->userMapper->update($user);
			}
		}
	}
}
