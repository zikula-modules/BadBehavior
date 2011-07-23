<?php
/*
Bad Behavior - detects and blocks unwanted Web accesses
Copyright (C) 2005-2006 Michael Hampton

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

As a special exemption, you may link this program with any of the
programs listed below, regardless of the license terms of those
programs, and distribute the resulting program, without including the
source code for such programs: ExpressionEngine

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.

Please report any problems to badbots AT ioerror DOT us
*/
if (defined('Zikula_Core::VERSION_NUM')) {

    define('BB2_CWD', dirname(__FILE__) . '/bad-behavior');

    // Bad Behavior callback functions.

    // Return current time in the format preferred by your database.
    function bb2_db_date() {
        return gmdate('Y-m-d H:i:s');	// Example is MySQL format
    }

    // Escape a string for database usage
    function bb2_db_escape($string) {
        return Datautil::formatForStore($string);	// No-op when database not in use.
    }

    // Run a query and return the results, if any.
    // Should return FALSE if an error occurred.
    // Bad Behavior will use the return value here in other callbacks.
    function bb2_db_query($query) {
        // only usage in vendor lib @2.0.43 is INSERT and SET and OPTIMIZE sqls
        $em = ServiceUtil::getService('doctrine.entitymanager');
        $conn = $em->getConnection();
        $stmt = $conn->prepare($query);
        return $stmt->execute();
    }

    // Return emergency contact email address.
    function bb2_email() {
        return System::getVar('adminmail');
    }

    // retrieve settings from database
    // Settings are hard-coded for non-database use
    function bb2_read_settings() {
        $bb2_settings = array(
            'log_table'     => 'badbehavior',
            'display_stats' => ModUtil::getVar('BadBehavior', 'display_stats'),
            'strict'        => ModUtil::getVar('BadBehavior', 'strict'),
            'verbose'       => ModUtil::getVar('BadBehavior', 'verbose'),
            'logging'       => ModUtil::getVar('BadBehavior', 'logging'),
            'httpbl_key' => '',
            'httpbl_threat' => '25',
            'httpbl_maxage' => '30',
            'offsite_forms' => false,
        );
        return $bb2_settings;
    }

    // write settings to database
    function bb2_write_settings($settings) {
        return;
    }

    // Screener
    // Insert this into the <head> section of your HTML through a template call
    // or whatever is appropriate. This is optional we'll fall back to cookies
    // if you don't use it.
    function bb2_insert_head() {
        // this is never functionally called in the bb2 core. it defines the
        // javascript, but never calls this function to include it in the page
        // if it is called manually here, the JS isn't defined yet...
        global $bb2_javascript;
        PageUtil::addVar('header', $bb2_javascript);
    }

    // Return the top-level relative path of wherever we are (for cookies)
    // You should provide in $url the top-level URL for your site.
    function bb2_relative_path() {
        $url = parse_url(System::getBaseUrl());
        return $url['path'] . '/';
    }


    // Calls inward to Bad Behavior itself.
    require_once(BB2_CWD . '/bad-behavior/version.inc.php');
    require_once(BB2_CWD . '/bad-behavior/core.inc.php');

    bb2_start(bb2_read_settings());

}