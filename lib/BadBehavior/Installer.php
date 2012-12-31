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

        $modvars = array(
            'enable' => true,
            'strict' => false,
            'verbose' => false,
            'logging' => true,
            'itemsperpage' => 25,
            'display_stats' => false);
        $this->setVars($modvars);

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
        switch ($oldversion) {
            case '2.0.33':
                EventUtil::registerPersistentModuleHandler('BadBehavior', 'core.init', array('BadBehavior_Listeners', 'init'));
                // remove table prefix
                $prefix = $this->serviceManager['prefix'];
                $connection = Doctrine_Manager::getInstance()->getConnection('default');
                $sql = 'RENAME TABLE ' . $prefix . '_bad_behavior' . " TO badbehavior";
                $stmt = $connection->prepare($sql);
                try {
                    $stmt->execute();
                } catch (Exception $e) {
                }
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