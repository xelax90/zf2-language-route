# Language Rotue for Zend Framework 2 and 3

Modue that adds a language to the beginning of every route and sets the selected
language in the MvcTranslator.

## Installation

Installation of LanguageRoute uses composer. For composer documentation, please 
refer to [getcomposer.org](http://getcomposer.org/).

```sh
composer require xelax90/zf2-language-route
```

Then add `ZF2LanguageRoute` to your `config/application.config.php`

To display the language switch you will need the 
[usrz/bootstrap-languages](https://github.com/usrz/bootstrap-languages) package. 
Add the languages.css to your stylesheets and place the languages.png in the 
same folder.

## Configuration

You can configure which prefix belongs to which language. For this, copy the 
config/zf2-language-route.global.php into your config/autoload folder and
edit the array. It should have the same order as your MvcTranslator 
configuration to correctly guess the default language.

You can also change the default route name that is used to switched the language
on a page with no RouteMatch instance (like 404).

## Usage

After installation the router will automatically add the language before any
assembled URL. It will also inject the 'locale' parameter into any RouteMatch
instance. To read it you can use the following example code inside any 
Controller

```php
$locale = $this->getEvent()->getRouteMatch()->getParam('locale');
```

The router will also check if the locale parameter is provided when assembling
a route. Use it to force a specific language prefix:

```php
$this->url()->fromRoute('home', ['locale' => 'de_DE']); // will point to /de
$this->url()->fromRoute('home', ['locale' => 'en_US']); // will point to /en
```

### Language Switch

The languageSwitch ViewHelper is able to render a simple dropdown to change the
current language. It tries to stay on the same page. If a 404 error is generated,
it will link to the `home` route. You can adjust the home route via configuration. 
Sample usage:

```php
echo $this->languageSwitch($renderMode, $currentLocale, $options);
```

All arguments are optional. 

The helper has four different rendering modes: 
* `LanguageSwitch::RENDER_TYPE_LIST_ITEM` (default): Bootstrap navigation dropdown list item
* `LanguageSwitch::RENDER_TYPE_NAVBAR`: Full Bootstrap navbar list with dropdown list item inside
* `LanguageSwitch::RENDER_TYPE_DIV`: DIV container with div elements for each option
* `LanguageSwitch::RENDER_TYPE_SELECT`: Select form element
* `LanguageSwitch::RENDER_TYPE_PARTIAL`: Custom partial ViewScript

You can pass options as array to modify classes output classes and other things. 
Please check the implementation for details about options.
