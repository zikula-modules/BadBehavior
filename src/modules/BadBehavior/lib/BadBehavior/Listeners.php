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
class BadBehavior_Listeners
{

    /**
     * Event listener for 'core.preinit' event.
     * 
     * @param Zikula_Event $event
     *
     * @return void
     */
    public function init(Zikula_Event $event)
    {
        // attach badbehavior screener
        require_once (DataUtil::formatForOS('modules/BadBehavior/lib/vendor/bad-behavior-zikula13.php'));
    }

}
