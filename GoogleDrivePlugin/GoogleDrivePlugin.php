<?php
use Netwerven\GoogleDrive\GoogleDrive;
use Netwerven\GoogleDrive\AdminSettings;
/**
 * @package WordPress Recruitment Framework
 */
/*
Plugin Name: Google Drive plugin for retrieving files
Plugin URI: http://www.netwerven.nl
Description: Adds functionalities for browsing/submitting a file from googleDrive
Version: 0.1
Author: 
Author URI: 
*/

// include classes
require('core/GoogleDrive.php');
require('core/ScriptRegister.php');
require('core/AdminSettings.php');

//initialize the admin settings
$settings = new AdminSettings();

// initialize the plugin class
GoogleDrive::getInstance();

