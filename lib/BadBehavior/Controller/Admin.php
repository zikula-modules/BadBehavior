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
        $this->redirect(ModUtil::url('BadBehavior', 'admin', 'view'));
    }

    public function view()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('BadBehavior::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        $offset = $this->request->getGet()->get('offset', 0);
        $count = $this->entityManager
                ->createQuery('SELECT COUNT(b.id) FROM BadBehavior_Entity_BadBehavior b')
                ->getSingleScalarResult();

        $items = $this->entityManager
                ->getRepository('BadBehavior_Entity_BadBehavior')
                ->getLog($offset - 1);

        return $this->view->assign('items', $items)
                ->assign('offset', $offset)
                ->assign('totalrows', $count)
                ->fetch('admin/view.tpl');
    }

    public function display()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('BadBehavior::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        $id = $this->request->getGet()->get('id', null);
        if ($id) {
            $item = $this->entityManager->find('BadBehavior_Entity_BadBehavior', $id);

            require_once(DataUtil::formatForOS('modules/BadBehavior/lib/vendor/bad-behavior-zikula13.php'));
            require_once(DataUtil::formatForOS('modules/BadBehavior/lib/vendor/bad-behavior/bad-behavior/responses.inc.php'));

            $message = bb2_get_response($item->getKey());

            return $this->view->assign($item->toArray())
                    ->assign('message', $message)
                    ->fetch('admin/display.tpl');
        }

        $this->throwNotFound(LogUtil::getErrorMsgArgs());
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
            'enable' => $this->request->getPost()->get('enable', false),
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