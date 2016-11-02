<?php

namespace ZF2LanguageRoute\Entity;

/**
 * Simple interface to store locale in user entity
 * @author schurix
 */
interface LocaleUserInterface {
	public function getLocale();
	
	public function setLocale($locale);
}
