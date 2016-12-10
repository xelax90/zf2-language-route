<?php
namespace ZF2LanguageRoute\Entity;

/**
 * Description of LocaleUserTrait
 *
 * @author schurix
 */
trait LocaleUserTrait{
	/** 
	 * Returns the stored user locale
	 * @see LocaleUserInterface
	 * @return string
	 */
	function getLocale() {
		return $this->locale;
	}

	/** 
	 * Sets the user locale
	 * @see LocaleUserInterface
	 * @param string $locale New locale
	 */
	function setLocale($locale) {
		$this->locale = $locale;
		return $this;
	}
}
