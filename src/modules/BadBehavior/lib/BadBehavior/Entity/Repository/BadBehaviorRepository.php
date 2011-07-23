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
use Doctrine\ORM\EntityRepository;

/**
 * Repository class for DQL calls
 *
 */
class BadBehavior_Entity_Repository_BadBehaviorRepository extends EntityRepository
{

    public function getLog($offset = 0, $orderBy = 'b.date', $sortDir = 'DESC')
    {
        $dql = "SELECT b FROM BadBehavior_Entity_BadBehavior b";
        $verbose = ModUtil::getVar('BadBehavior', 'verbose');
        if (!$verbose) {
            $dql .= " WHERE b.key != '00000000'";
        }
        $dql .= " ORDER BY $orderBy $sortDir";

        $em = ServiceUtil::getService('doctrine.entitymanager');
        $query = $em->createQuery($dql);

        $limit = ModUtil::getVar('BadBehavior', 'itemsperpage');
        if ($limit > 0) {
            $query->setMaxResults($limit);
        }
        if ($offset > 0) {
            $query->setFirstResult($offset);
        }
        $result = $query->getArrayResult(); // hydrate result to array

        require_once (DataUtil::formatForOS('modules/BadBehavior/lib/vendor/bad-behavior-zikula13.php'));
        require_once (DataUtil::formatForOS('modules/BadBehavior/lib/vendor/bad-behavior/bad-behavior/responses.inc.php'));

        foreach ($result as $key => $item) {
            $result[$key]['message'] = bb2_get_response($item['key']);
        }

        return $result;
    }

}