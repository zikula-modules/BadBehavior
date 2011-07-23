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
        // attach badbehavior screener
        require_once (DataUtil::formatForOS('modules/BadBehavior/lib/vendor/bad-behavior-zikula13.php'));
    }
}
