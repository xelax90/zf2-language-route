<?php

/*
 * Copyright (C) 2015 schurix
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

namespace ZF2LanguageRoute\Mvc\Router\Http;

use Zend\Mvc\I18n\Router\TranslatorAwareTreeRouteStack;
use Zend\Router\Http\RouteMatch;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\Stdlib\RequestInterface;
use ZF2LanguageRoute\Options\LanguageRouteOptions;
use Zend\Authentication\AuthenticationServiceInterface;
use ZF2LanguageRoute\Entity\LocaleUserInterface;

/**
 * Manages multilanguage routes by adding a language key to the baseUrl
 *
 * @author schurix
 */
class LanguageTreeRouteStack extends TranslatorAwareTreeRouteStack {
	
	/** @var LanguageRouteOptions */
	protected $languageOptions;
	
	/** @var AuthenticationServiceInterface */
	protected $authenticationService;
	
	function getLanguageOptions() {
		return $this->languageOptions;
	}

	function setLanguageOptions(LanguageRouteOptions $languageOptions) {
		$this->languageOptions = $languageOptions;
	}
	
	function getAuthenticationService() {
		return $this->authenticationService;
	}

	function setAuthenticationService(AuthenticationServiceInterface $authenticationService) {
		$this->authenticationService = $authenticationService;
	}

    /**
     * assemble(): defined by \Zend\Mvc\Router\RouteInterface interface.
     *
     * @see    \Zend\Mvc\Router\RouteInterface::assemble()
     * @param  array $params
     * @param  array $options
     * @return mixed
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
	public function assemble(array $params = array(), array $options = array()) {
		// Assuming, this stack can only orrur on top level
		// TODO is there any way to ensure that this is called only for top level?
		
		// get translator
		$translator = null;
		if(isset($options['translator'])){
			$translator = $options['translator'];
		} elseif($this->hasTranslator() && $this->isTranslatorEnabled()){
			$translator = $this->getTranslator();
		}
		
		$languages = $this->getRouteLanguages();
		
		$oldBase = $this->baseUrl; // save old baseUrl
		// only add language key when more than one language is supported
		if(count($languages) > 1){
			if(isset($params['locale'])){
				// use parameter if provided
				$locale = $params['locale'];
				// get key for locale
				$key = array_search($locale, $languages); 
			} elseif(is_callable(array($translator, 'getLocale'))){
				// use getLocale if possible
				$locale = $translator->getLocale();
				// get key for locale
				$key = array_search($locale, $languages); 
			}
			
			if(!empty($key)){
				// add key to baseUrl
				$this->setBaseUrl($oldBase . '/'.$key); 
			}
		}
		
		$res = parent::assemble($params, $options);
		// restore baseUrl
		$this->setBaseUrl($oldBase); 
		return $res;
	}
	
    /**
     * match(): defined by \Zend\Mvc\Router\RouteInterface
     *
     * @see    \Zend\Mvc\Router\RouteInterface::match()
     * @param  Request      $request
     * @param  integer|null $pathOffset
     * @param  array        $options
     * @return RouteMatch|null
     */
	public function match(RequestInterface $request, $pathOffset = null, array $options = array()) {
		// Languages should only be added on top level. Since there seems to be 
		// no way to ensure this stack is only at top level, the language has
		// top be checked every time this method is called.
		/* if($pathOffset !== null){
			return parent::match($request, $pathOffset, $options);
		} */
		
        if (!method_exists($request, 'getUri')) {
            return null;
        }
		
        if ($this->baseUrl === null && method_exists($request, 'getBaseUrl')) {
            $this->setBaseUrl($request->getBaseUrl());
        }
		
		/* @var $translator TranslatorInterface */
		$translator = null;
		if(isset($options['translator'])){
			$translator = $options['translator'];
		} elseif($this->hasTranslator() && $this->isTranslatorEnabled()){
			$translator = $this->getTranslator();
		}
		
		$languages = $this->getRouteLanguages();
		$languageKeys = array_keys($languages);
		
		// save old baseUrl
		$oldBase = $this->baseUrl; 
		$locale = null;
		
		// extract /-separated path parts
		$uri = $request->getUri();
		$baseUrlLength = strlen($this->baseUrl);
		$path = ltrim(substr($uri->getPath(), $baseUrlLength), '/');
		$pathParts = explode('/', $path);
		
		// check if language was provided in first part
		if(count($languages) > 1 && in_array($pathParts[0], $languageKeys)){
			// if language was provided, save the locale and adjust the baseUrl
			$locale = $languages[$pathParts[0]];
			$this->setBaseUrl($oldBase . '/'.$pathParts[0]);
			if(is_callable(array($translator, 'setLocale'))){
				// change translator locale
				$translator->setLocale($locale);
			}
		} elseif(!empty($this->getAuthenticationService()) && $this->getAuthenticationService()->hasIdentity()) {
			// try to get user language if no language was provided by url
			$user = $this->getAuthenticationService()->getIdentity();
			if($user instanceof LocaleUserInterface){
				$userLocale = $user->getLocale();
				if(in_array($userLocale, $languages)){
					$locale = $userLocale;
				}
			}

		}
		if(empty($locale) && !empty($translator) && is_callable(array($translator, 'getLocale'))){
			// If stil no language found, check the translator locale
			$locale = $translator->getLocale();
		}
		
		$res = parent::match($request, $pathOffset, $options);
		$this->setBaseUrl($oldBase);
		if($res instanceof RouteMatch && !empty($locale)){
			$res->setParam('locale', $locale);
		}
		return $res;
	}
	
	protected function getRouteLanguages(){
		if(!empty($this->getLanguageOptions())){
			return $this->getLanguageOptions()->getLanguages();
		}
		return [];
	}
	
}
