<?php

namespace Appsco\Dashboard\ApiBundle\Security\Core\Authentication\Token;

use Appsco\Dashboard\ApiBundle\Model\Account;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class AppscoToken extends AbstractToken
{
    /** @var  Account */
    protected $account;

    /** @var  string */
    protected $accessToken;

    /** @var  string */
    protected $idToken;

    /**
     * @param mixed $user
     * @param array $roles
     * @param Account|null $account
     * @param string|null $accessToken
     * @param string|null $idToken
     */
    public function __construct($user, array $roles = array(), Account $account = null, $accessToken = null, $idToken = null)
    {
        parent::__construct($roles);

        $this->setUser($user);

        $this->account = $account;
        $this->accessToken = $accessToken;
        $this->idToken = $idToken;

        parent::setAuthenticated(count($roles) > 0);
    }

    /**
     * {@inheritdoc}
     */
    public function setAuthenticated($isAuthenticated)
    {
        if ($isAuthenticated) {
            throw new \LogicException('Cannot set this token to trusted after instantiation.');
        }

        parent::setAuthenticated(false);
    }

    /**
     * Returns the user credentials.
     *
     * @return mixed The user credentials
     */
    public function getCredentials()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @return string
     */
    public function getIdToken()
    {
        return $this->idToken;
    }

    /**
     * @return \Appsco\Dashboard\ApiBundle\Model\Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        $accountStr = serialize($this->account);
        $result = serialize(array($accountStr, $this->accessToken, $this->idToken, parent::serialize()));
        return $result;
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list($accountStr, $this->accessToken, $this->idToken, $parentStr) = unserialize($serialized);
        $this->account = unserialize($accountStr);
        parent::unserialize($parentStr);
    }

}