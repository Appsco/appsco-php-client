<?php

namespace Appsco\Dashboard\ApiBundle\OAuth;

use Appsco\Dashboard\ApiBundle\Client\AppscoClient;
use Appsco\Dashboard\ApiBundle\Error\AppscoOAuthException;
use Appsco\Dashboard\ApiBundle\Model\AccessData;
use Appsco\Dashboard\ApiBundle\Model\Account;
use Appsco\Dashboard\ApiBundle\Security\Core\Authentication\Token\AppscoToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class AppscoOAuth implements AppscoOAuthInterface
{
    /** @var  AppscoClient */
    protected $client;

    /** @var  SessionInterface */
    protected $session;

    /**
     * @param AppscoClient $client
     * @param SessionInterface $session
     */
    public function __construct(AppscoClient $client, SessionInterface $session)
    {
        $this->client = $client;
        $this->session = $session;
    }

    /**
     * @return AppscoClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param array|string[] $scope
     * @param string|null $redirectUri
     * @return RedirectResponse
     */
    public function start(array $scope = array(), $redirectUri = null)
    {
        $state = $this->generateState();

        $url = $this->client->getAuthorizeUrl($state, $scope, $redirectUri);

        return new RedirectResponse($url);
    }

    /**
     * @param Request $request
     * @param string|null $redirectUri
     * @throws \Symfony\Component\Security\Core\Exception\AuthenticationException
     * @throws AppscoOAuthException
     * @return AppscoToken
     */
    public function callback(Request $request, $redirectUri = null)
    {
        $code = $request->get('code');
        $state = $request->get('state');

        $this->validateState($state);
        $this->checkError($request);

        $accessData = $this->client->getAccessData($code, $redirectUri);
        $profile = $this->client->profileRead('me');

        if (false == $profile) {
            throw new AuthenticationException('Unable to get profile info from Appsco');
        }

        return $this->createToken($accessData, $profile);
    }

    /**
     * @param Request $request
     * @throws AppscoOAuthException
     */
    protected function checkError(Request $request)
    {
        if ($error = $request->query->get('error')) {
            throw new AppscoOAuthException($error, $request->query->get('error_description'));
        }
    }

    /**
     * @return string
     */
    protected function generateState()
    {
        $state = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);

        $this->session->set($this->getStateSessionKey(), $state);

        return $state;
    }

    /**
     * @param string $state
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    protected function validateState($state)
    {
        $savedState = $this->session->get($this->getStateSessionKey());

        if ($savedState != $state) {
            throw new BadRequestHttpException('Invalid state');
        }
    }

    protected function getStateSessionKey()
    {
        return 'appsco_oauth_state';
    }

    /**
     * @param \Appsco\Dashboard\ApiBundle\Model\AccessData $accessData
     * @param \Appsco\Dashboard\ApiBundle\Model\Account $account
     * @return AppscoToken
     */
    protected function createToken(AccessData $accessData, Account $account)
    {
        $result = new AppscoToken($account, array(), $account, $accessData->getAccessToken(), $accessData->getIdToken());
        return $result;
    }

}