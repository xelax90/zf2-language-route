<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ZF2LanguageRoute\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Description of LanguageRouteOptions
 *
 * @author schurix
 */
class LanguageRouteOptions extends AbstractOptions{
	
	/**
	 * Array of languages allowed for language route. The key is the prefix 
	 * which is attached to the url (e.g. en), the value is the associated 
	 * locale  (e.g. 'en_US')
	 * @var array
	 */
	protected $languages = ['de' => 'de_DE', 'en' => 'en_US'];
	
	function getLanguages() {
		return $this->languages;
	}

	function setLanguages(array $languages) {
		$this->languages = $languages;
	}
}
