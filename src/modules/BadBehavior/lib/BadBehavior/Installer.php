<?php
/**
 * Copyright Zikula Foundation 2010 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license MIT
 * @package ZikulaExamples_BadBehavior
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

/**
 * Installer.
 */
class BadBehavior_Installer extends Zikula_AbstractInstaller
{

    /**
     * Install the BadBehavior module.
     *
     * This function is only ever called once during the lifetime of a particular
     * module instance.
     *
     * @return boolean True on success, false otherwise.
     */
    public function install()
    {
        // create the table
        try {
            DoctrineHelper::createSchema($this->entityManager, array('BadBehavior_Entity_BadBehavior'));
        } catch (Exception $e) {
            return false;
        }
        
        EventUtil::registerPersistentModuleHandler('BadBehavior', 'core.init', array('BadBehavior_Listeners', 'init'));

        // Initialisation successful
        return true;
    }

    /**
     * Upgrade the module from an old version.
     *
     * This function can be called multiple times.
     *
     * @param integer $oldversion Version to upgrade from.
     *
     * @return boolean True on success, false otherwise.
     */
    public function upgrade($oldversion)
    {
        switch ($oldversion)
        {
            case '2.0.33':
                EventUtil::registerPersistentModuleHandler('BadBehavior', 'core.init', array('BadBehavior_Listeners', 'init'));
            case '2.0.43':
                // future upgrades

        }

        // Update successful
        return true;
    }

    /**
     * Uninstall the module.
     *
     * This function is only ever called once during the lifetime of a particular
     * module instance.
     *
     * @return bool True on success, false otherwise.
     */
    public function uninstall()
    {
        // drop table
        DoctrineHelper::dropSchema($this->entityManager, array('BadBehavior_Entity_BadBehavior'));
        EventUtil::unregisterPersistentModuleHandlers('BadBehavior');

        // remove all module vars
        $this->delVars();

        // Deletion successful
        return true;
    }

}