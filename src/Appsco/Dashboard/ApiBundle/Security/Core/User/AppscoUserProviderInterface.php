<?php

namespace Appsco\Dashboard\ApiBundle\Security\Core\User;

use Appsco\Dashboard\ApiBundle\Security\Core\Authentication\Token\AppscoToken;
use Symfony\Component\Security\Core\User\UserInterface;

interface AppscoUserProviderInterface
{
    /**
     * @param \Appsco\Dashboard\ApiBundle\Security\Core\Authentication\Token\AppscoToken $token
     * @return UserInterface
     */
    public function create(AppscoToken $token);

}