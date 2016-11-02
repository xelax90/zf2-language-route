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
use Zend\Mvc\MvcEvent;

/**
 * Description of RouteListener
 *
 * @author schurix
 */
class RouteListener extends AbstractListenerAggregate{
	
	/** @var LanguageRouteOptions */
	protected $options;
	
	function __construct(LanguageRouteOptions $options) {
		$this->options = $options;
	}
	
	public function attach(EventManagerInterface $events, $priority = 1) {
		$this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'onRoute'], $priority);
	}
	
	public function onRoute(){
	}
}
