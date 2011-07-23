<?php

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
        if (ModUtil::getVar('BadBehavior', 'enabled')) {
            // attach badbehavior screener
            require_once (DataUtil::formatForOS('modules/BadBehavior/lib/vendor/bad-behavior-zikula13.php'));
        }
    }
}
