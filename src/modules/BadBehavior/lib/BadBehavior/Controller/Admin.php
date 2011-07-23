<?php
class BadBehavior_Controller_Admin extends Zikula_AbstractController
{

    /**
     * the main administration function
     * This function is the default function, and is called whenever the
     * module is initiated without defining arguments.
     */
    public function main()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('BadBehavior::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
        $this->redirect(ModUtil::url('BadBehavior', 'admin', 'modifyconfig'));
    }

    /**
     * @desc present administrator options to change module configuration
     * @return      config template
     */
    public function modifyconfig()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('BadBehavior::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        return $this->view->fetch('admin/modifyconfig.tpl');
    }

    /**
     * @desc sets module variables as requested by admin
     * @return      status/error ->back to modify config page
     */
    public function updateconfig()
    {
        $this->checkCsrfToken();

        $this->throwForbiddenUnless(SecurityUtil::checkPermission('BadBehavior::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        $modvars = array(
            'enabled' => $this->request->getPost()->get('enabled', false),
            'strict' => $this->request->getPost()->get('strict', false),
            'logging' => $this->request->getPost()->get('logging', false),
            'verbose' => $this->request->getPost()->get('verbose', false),
            'display_stats' => $this->request->getPost()->get('display_stats', false),
            'itemsperpage' => $this->request->getPost()->get('itemsperpage', 25),
        );

        // set the new variables
        $this->setVars($modvars);

        // clear the cache
        $this->view->clear_cache();

        LogUtil::registerStatus($this->__('Done! Updated the BadBehavior configuration.'));
        $this->redirect(ModUtil::url('BadBehavior', 'admin', 'modifyconfig'));
    }

    /**
     * @desc set caching to false for all admin functions
     * @return      null
     */
    public function postInitialize()
    {
        $this->view->setCaching(false);
    }

}