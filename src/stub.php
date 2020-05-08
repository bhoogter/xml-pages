<?php
// Place the dependency manager phar in the same directory ()
define('DEPENDENCY_MANAGER_PHAR', __DIR__ . "/phars/php-dependency-manager.phar");
require_once("phar://" . DEPENDENCY_MANAGER_PHAR . "/src/class-dependency-manager.php");
dependency_manager(__DIR__ . "/dependencies.xml", __DIR__ . "/phars/");

spl_autoload_register(function ($name) {
    $d = (strpos(__FILE__, ".phar") === false ? __DIR__ : "phar://" . __FILE__ . "/src");
    if ($name == "xml_serve") require_once($d . "/class-xml-serve.php");
    if ($name == "page_handlers") require_once($d . "/class-page-handlers.php");
    if ($name == "page_source") require_once($d . "/class-page-source.php");
    if ($name == "site_settings") require_once($d . "/class-site-settings.php");
    if ($name == "xml_serve_extensions") require_once($d . "/class-extensions.php");
});

__HALT_COMPILER();
