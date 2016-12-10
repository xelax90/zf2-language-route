<?php

namespace ZF2LanguageRoute\Entity;

/**
 * Simple interface to store locale in user entity
 * @author schurix
 */
interface LocaleUserInterface {
	/** 
	 * Returns the stored user locale
	 * @return string
	 */
	public function getLocale();
	
	/** 
	 * Sets the user locale
	 * @param string $locale New locale
	 */
	public function setLocale($locale);
}
