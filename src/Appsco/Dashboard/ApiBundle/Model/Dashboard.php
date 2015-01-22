<?php

namespace Appsco\Dashboard\ApiBundle\Model;

use JMS\Serializer\Annotation as JMS;

class Dashboard
{
    /**
     * @var int
     * @JMS\Type("integer")
     */
    protected $id;

    /**
     * @var int
     * @JMS\Type("integer")
     */
    protected $roleId;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $alias;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $title;

    /**
     * @var string[]
     * @JMS\Type("array<string>")
     */
    protected $roles = [];

    /**
     * @var bool
     * @JMS\Type("boolean")
     */
    protected $defaultDashboard;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $url;

    /**
     * @var string|null
     * @JMS\Type("string")
     */
    protected $logoUrl;

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param \string[] $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return \string[]
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param int $roleId
     */
    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;
    }

    /**
     * @return int
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * @param null|string $logoUrl
     */
    public function setLogoUrl($logoUrl)
    {
        $this->logoUrl = $logoUrl;
    }

    /**
     * @return null|string
     */
    public function getLogoUrl()
    {
        return $this->logoUrl;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param boolean $defaultDashboard
     */
    public function setDefaultDashboard($defaultDashboard)
    {
        $this->defaultDashboard = $defaultDashboard;
    }

    /**
     * @return boolean
     */
    public function getDefaultDashboard()
    {
        return $this->defaultDashboard;
    }

    /**
     * @param string $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }
} 