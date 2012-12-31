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
 * Version.
 */
class BadBehavior_Version extends Zikula_AbstractVersion
{

    /**
     * Module meta data.
     *
     * @return array Module metadata.
     */
    public function getMetaData()
    {
        $meta = array();
        $meta['displayname'] = $this->__('BadBehavior');
        $meta['description'] = $this->__("BadBehavior");
        //! module name that appears in URL
        $meta['url'] = $this->__('badbehavior');
        $meta['version'] = '2.0.43';
        $meta['oldnames'] = array('Bad_Behaviour');
        $meta['core_min'] = '1.3.0'; // requires minimum 1.3.0 or later
        $meta['securityschema'] = array('BadBehavior::' => '::',
            'BadBehavior:User:' => 'UserName::');
        return $meta;
    }

}
