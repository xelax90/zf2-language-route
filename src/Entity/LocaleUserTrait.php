<?php
namespace ZF2LanguageRoute\Entity;

/**
 * Description of LocaleUserTrait
 *
 * @author schurix
 */
trait LocaleUserTrait{
	protected $locale;
	
	function getLocale() {
		return $this->locale;
	}

	function setLocale($locale) {
		$this->locale = $locale;
	}
}
