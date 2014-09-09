<?php
/* 
Plugin Name: WPEX Replace DB Urls
Plugin URI: http://wpexperte.de/wpex-replace
Description: Replace one ore more urls in the complete database. Serialized strings are also supported.
Text Domain: wpex-replace
Domain Path: /languages
Author: Detlef Stöver
Version: 0.2.0
Author URI: http://www.wpexperte.de
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there! I\'m just a plugin, not much I can do when called directly.';
	exit;
}

if (!defined('WPEX_REPLACE_VERSION'))
{
	define('WPEX_REPLACE_VERSION', '0.2.0');
}

// min version 5 supported	
if (version_compare(PHP_VERSION, '5.3.0', '<'))
{
  exit('<p>'.__('WPEX-Replace plugin requires PHP 5.3 or higher.', 'wpex-replace').'</p>');
}

define('WPEX_REPLACE_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
define('WPEX_REPLACE_PLUGIN_URL', plugin_dir_url('') . basename(__DIR__) . '/');

if (is_admin())
{
	require_once(WPEX_REPLACE_PLUGIN_DIR.'/admin.php');
	require_once(WPEX_REPLACE_PLUGIN_DIR.'/replacestring.class.php');
}

/**
 * simple logging 
 */
//function logger($msg)
//{
//	$logFile = "/var/log/wp.local.log";
//	if (!file_exists($logFile))
//	{
//		file_put_contents($logFile, '');
//	}
//	
//	if (is_object($msg) || is_array($msg))
//		$msg = var_export($msg, true);
//		
//	$out = sprintf("%s %-8s %s\n", date('Y-m-d H:m:s'), strtoupper($mode), $msg);
//	error_log($out, 3, $logFile);
//	
//	// if warn or error send mail to support
//	if (strpos('error|warn', $mode) !== false)
//	{
//		$to = utf8_decode("wp.local <support@west-business-group.com>");
//		
//		$headers   = array();
//		$headers[] = 'Content-type: text/html; charset="utf-8";';
//		$headers[] = 'From: Log Entry <logging@wp.local>';
//		
//		mail($to, 'Log entry from wp.local', $out, implode("\n", $headers));
//	}
//}