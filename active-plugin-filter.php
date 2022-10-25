<?php

/*
Plugin Name: Active Plugin Filter
Plugin URI: https://github.com/pie/active-plugin-filter
Description: A must-use plugin to prevent plugins from loading when specific runtime conditions are met. Fill it up with your own exclusions and conditions.
Version: 0.0.1
Author: The team at PIE
Author URI: https://pie.co.de
*/

namespace PIE\ActivePluginFilter;

add_filter('option_active_plugins', __namespace__ . '\filter_active_plugins');

/**
 * Filters active plugins if necessary, otherwise returns the supplied array. Here is where you can add your conditions
 * for filtering, and return the array of plugin identifiers you wish to be active under those conditions.
 * A plugin identifier is the path to the plugin file relative to the plugins directory, for example:
 * akismet/akismet.php
 *
 * @param Array $plugins An array of the plugin identifiers which would be active without this filter
 * @return Array The array of plugin identifiers which will be regarded as active
 */
function filter_active_plugins($plugins)
{

    // We always return the default list of active plugins in the admin area. You can remove this if you like
    if (is_admin()) {
        return $plugins;
    }

    // If we don't meet our condition for filtering plugins then we can return the default list
    if (!some_condition_is_met()) {
        return $plugins;
    }

    // This example loop iterates over an array of plugin identifiers and removes those which are found in the default
    // list
    foreach (get_unnecessary_plugins() as $plugin) {
        $k = array_search($plugin, $plugins);
        if (false !== $k) {
            unset($plugins[$k]);
        }
    }

    return $plugins;
}

/**
 * Returns an array of plugin identifiers that we wish to not activate for the context in question.
 *
 * @return Array $unnecessary_plugins an array of plugin directory/file identifiers
 */
function get_unnecessary_plugins()
{

    $unnecessary_plugins = array();
    $unnecessary_plugins[] = "akismet/akismet.php";

    return  $unnecessary_plugins;
}

/**
 * Test if some arbitrary condition is met
 *
 * @return boolean
 */
function some_condition_is_met()
{
    return "the sky" === "blue";
}

/**
 * Test if the request URI is in wp-admin
 *
 * @return boolean
 */
function is_admin()
{
    return strpos(get_request_uri(), '/wp-admin/');
}

// returns the path of the request URI without the query string
// see http://php.net/manual/en/function.parse-url.php
// and http://php.net/manual/en/reserved.variables.server.php
// and http://php.net/manual/en/url.constants.php
function get_request_uri()
{
    return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
}
