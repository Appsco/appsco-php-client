<?php

namespace Appsco\Dashboard\ApiBundle\Model;

use JMS\Serializer\Annotation as JMS;

class DashboardIcon
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
    protected $applicationId;

    /**
     * @var int
     * @JMS\Type("integer")
     */
    protected $applicationTemplateId;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $title;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $url;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $iconUrl;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $authType;

    /**
     * @var boolean
     * @JMS\Type("boolean")
     */
    protected $urlEditable;

    /**
     * @var boolean
     * @JMS\Type("boolean")
     */
    protected $isConfigured;

    /**
     * @var array
     * @JMS\Type("array")
     */
    protected $claims = [];

    /**
     * @return array
     */
    public function getClaims()
    {
        return $this->claims;
    }

    /**
     * @param array $claims
     */
    public function setClaims($claims)
    {
        $this->claims = $claims;
    }

    /**
     * @return boolean
     */
    public function isConfigured()
    {
        return $this->isConfigured;
    }

    /**
     * @param boolean $isConfigured
     */
    public function setIsConfigured($isConfigured)
    {
        $this->isConfigured = $isConfigured;
    }

    /**
     * @return boolean
     */
    public function isUrlEditable()
    {
        return $this->urlEditable;
    }

    /**
     * @param boolean $urlEditable
     */
    public function setUrlEditable($urlEditable)
    {
        $this->urlEditable = $urlEditable;
    }

    /**
     * @return string
     */
    public function getAuthType()
    {
        return $this->authType;
    }

    /**
     * @param string $authType
     */
    public function setAuthType($authType)
    {
        $this->authType = $authType;
    }

    /**
     * @return string
     */
    public function getIconUrl()
    {
        return $this->iconUrl;
    }

    /**
     * @param string $iconUrl
     */
    public function setIconUrl($iconUrl)
    {
        $this->iconUrl = $iconUrl;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getApplicationTemplateId()
    {
        return $this->applicationTemplateId;
    }

    /**
     * @param int $applicationTemplateId
     */
    public function setApplicationTemplateId($applicationTemplateId)
    {
        $this->applicationTemplateId = $applicationTemplateId;
    }

    /**
     * @return int
     */
    public function getApplicationId()
    {
        return $this->applicationId;
    }

    /**
     * @param int $applicationId
     */
    public function setApplicationId($applicationId)
    {
        $this->applicationId = $applicationId;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}