<?php

/**
 * QLTS v1
 * 
 * @version 1.0
 * @license
 */
/**
 * Index file
 * 
 * @package Application
 * @version 1.0
 * @author OanhNN
 * 
 * @todo Define APPLICATION_PATH, APPLICATION_ENV and create application, bootstrap and run
 */
// Define path to application directory
defined('APPLICATION_PATH')
        || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/application'));

// Define application environment
defined('APPLICATION_ENV')
        || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Define template name
defined('TEMPLATE_NAME')
        || define('TEMPLATE_NAME', (getenv('TEMPLATE_NAME') ? getenv('TEMPLATE_NAME') : 'default'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
            realpath(APPLICATION_PATH . '/../library'),
            get_include_path(),
        )));

/** Zend_Application */
/**
 * @see Zend_Application
 */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
                APPLICATION_ENV,
                APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
        ->run();