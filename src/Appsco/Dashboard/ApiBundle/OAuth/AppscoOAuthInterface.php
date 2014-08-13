<?php

namespace Appsco\Dashboard\ApiBundle\OAuth;

use Appsco\Dashboard\ApiBundle\Error\AppscoOAuthException;
use Appsco\Dashboard\ApiBundle\Security\Core\Authentication\Token\AppscoToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

interface AppscoOAuthInterface
{
    /**
     * @return \Appsco\Dashboard\ApiBundle\Client\AccountsClient
     */
    public function getClient();

    /**
     * @param array|string[] $scope
     * @param string|null $redirectUri
     * @return RedirectResponse
     */
    public function start(array $scope = array(), $redirectUri = null);

    /**
     * @param Request $request
     * @param string|null $redirectUri
     * @throws \Symfony\Component\Security\Core\Exception\AuthenticationException
     * @throws AppscoOAuthException
     * @return AppscoToken
     */
    public function callback(Request $request, $redirectUri = null);

}