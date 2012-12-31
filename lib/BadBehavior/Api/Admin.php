<?php

/**
 * BadBehavior - an implementation of the bad-behavior php library
 * for the Zikula Application Framework
 * 
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */
class BadBehavior_Api_Admin extends Zikula_AbstractApi
{

    /**
     * Get available admin panel links
     *
     * @return array array of admin links
     */
    public function getlinks()
    {
        // Define an empty array to hold the list of admin links
        $links = array();

        if (SecurityUtil::checkPermission('BadBehavior::', '::', ACCESS_ADMIN)) {
            $links[] = array(
                'url' => ModUtil::url('BadBehavior', 'admin', 'view'),
                'text' => $this->__('Access log'),
                'class' => 'z-icon-es-view');
        }

        if (SecurityUtil::checkPermission('BadBehavior::', '::', ACCESS_ADMIN)) {
            $links[] = array(
                'url' => ModUtil::url('BadBehavior', 'admin', 'modifyconfig'),
                'text' => $this->__('Settings'),
                'class' => 'z-icon-es-config');
        }

        return $links;
    }

}