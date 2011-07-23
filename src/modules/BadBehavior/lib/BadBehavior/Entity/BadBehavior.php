<?php

/**
 * Copyright Zikula Foundation 2010 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package ZikulaExamples_BadBehavior
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * BadBehavior entity class.
 *
 * @ORM\Entity(repositoryClass="BadBehavior_Entity_Repository_BadBehaviorRepository")
 * @ORM\Table(name="badbehavior",indexes={@ORM\Index(name="ip_idx", columns={"ip"}),@ORM\Index(name="user_agent_idx", columns={"user_agent"})})
 */
class BadBehavior_Entity_BadBehavior extends Zikula_EntityAccess
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column
     */
    private $ip;
    /**
     * @ORM\Column(name="`date`", type="datetime")
     */
    private $date = '0000-00-00 00:00:00';
    /**
     * @ORM\Column
     */
    private $request_method;
    /**
     * @ORM\Column
     */
    private $request_uri;
    /**
     * @ORM\Column
     */
    private $server_protocol;
    /**
     * @ORM\Column
     */
    private $http_headers;
    /**
     * @ORM\Column
     */
    private $user_agent;
    /**
     * @ORM\Column
     */
    private $request_entity;
    /**
     * @ORM\Column(name="`key`")
     */
    private $key;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getRequest_method()
    {
        return $this->request_method;
    }

    public function setRequest_method($request_method)
    {
        $this->request_method = $request_method;
    }

    public function getRequest_uri()
    {
        return $this->request_uri;
    }

    public function setRequest_uri($request_uri)
    {
        $this->request_uri = $request_uri;
    }

    public function getServer_protocol()
    {
        return $this->server_protocol;
    }

    public function setServer_protocol($server_protocol)
    {
        $this->server_protocol = $server_protocol;
    }

    public function getHttp_headers()
    {
        return $this->http_headers;
    }

    public function setHttp_headers($http_headers)
    {
        $this->http_headers = $http_headers;
    }

    public function getUser_agent()
    {
        return $this->user_agent;
    }

    public function setUser_agent($user_agent)
    {
        $this->user_agent = $user_agent;
    }

    public function getRequest_entity()
    {
        return $this->request_entity;
    }

    public function setRequest_entity($request_entity)
    {
        $this->request_entity = $request_entity;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($key)
    {
        $this->key = $key;
    }

}
