<?php

/**
 * Class to control Admin Api
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
                'url' => ModUtil::url('BadBehavior', 'admin', 'modifyconfig'),
                'text' => $this->__('Settings'),
                'class' => 'z-icon-es-config');
        }

        if (SecurityUtil::checkPermission('BadBehavior::', '::', ACCESS_ADMIN)) {
            $links[] = array(
                'url' => ModUtil::url('BadBehavior', 'admin', 'view'),
                'text' => $this->__('Access log'),
                'class' => 'z-icon-es-view');
        }

        return $links;
    }

}