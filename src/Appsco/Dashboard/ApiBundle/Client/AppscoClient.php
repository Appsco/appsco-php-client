<?php

namespace Appsco\Dashboard\ApiBundle\Client;

use Appsco\Dashboard\ApiBundle\Model\AccessData;
use Appsco\Dashboard\ApiBundle\Model\Account;
use Appsco\Dashboard\ApiBundle\Model\CertificateList;
use Appsco\Dashboard\ApiBundle\Model\Dashboard;
use Appsco\Dashboard\ApiBundle\OAuth\Scopes;
use BWC\Share\Net\HttpClient\HttpClientInterface;
use BWC\Share\Net\HttpStatusCode;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AppscoClient 
{
    const AUTH_TYPE_ACCESS_TOKEN = 1;
    const AUTH_TYPE_BASIC_AUTH = 2;
    const AUTH_TYPE_REQUEST = 3;


    /** @var  HttpClientInterface */
    protected $httpClient;

    /** @var string */
    protected $scheme = 'https';

    /** @var  string */
    protected $domain = 'accounts.appsco.com';

    /** @var string */
    protected $sufix = '';

    /** @var  SerializerInterface */
    protected $serializer;

    /** @var  string */
    protected $defaultRedirectUri;

    /** @var  string */
    protected $accessToken = null;

    /** @var  string */
    protected $clientId;

    /** @var  string */
    protected $clientSecret;

    /** @var  LoggerInterface|null */
    protected $logger;

    /** @var integer */
    protected $authType;

    public function __construct(
        HttpClientInterface $httpClient,
        SerializerInterface $serializer,
        $scheme,
        $domain,
        $sufix,
        $defaultRedirectUri,
        $clientId,
        $clientSecret,
        $authType,
        LoggerInterface $logger = null
    ) {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
        $this->scheme = $scheme;
        $this->domain = $domain;
        $this->sufix = $sufix;
        $this->defaultRedirectUri = $defaultRedirectUri;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->authType = $authType;
        $this->logger = $logger;
    }


    /**
     * @param string $accessToken
     * @return $this|AppscoClient
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param string $clientId
     * @return $this|AppscoClient
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param string $clientSecret
     * @return $this|AppscoClient
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param string $redirectUri
     * @return $this|AppscoClient
     */
    public function setDefaultRedirectUri($redirectUri)
    {
        $this->defaultRedirectUri = $redirectUri;
        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultRedirectUri()
    {
        return $this->defaultRedirectUri;
    }

    /**
     * @param int $authType
     * @return $this
     */
    public function setAuthType($authType)
    {
        $this->authType = $authType;
        return $this;
    }

    /**
     * @return int
     */
    public function getAuthType()
    {
        return $this->authType;
    }

    /**
     * @param string $state
     * @param array|string[] $scope
     * @param string $redirectUri
     * @return string
     */
    public function getAuthorizeUrl($state, array $scope = array(), $redirectUri = null)
    {
        if (empty($scope)) {
            $scope = array(Scopes::PROFILE_READ);
        }

        $redirectUri = $redirectUri ? $redirectUri : $this->getDefaultRedirectUri();

        $url = sprintf('%s://%s%s/oauth/authorize?client_id=%s&response_type=code&scope=%s&redirect_uri=%s&state=%s',
            $this->scheme,
            $this->domain,
            $this->sufix,
            $this->getClientId(),
            implode(' ', $scope),
            $redirectUri,
            $state
        );

        return $url;
    }

    /**
     * @param string $code
     * @param string $redirectUri
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @return AccessData
     */
    public function getAccessData($code, $redirectUri = null)
    {
        $redirectUri = $redirectUri ? $redirectUri : $this->getDefaultRedirectUri();

        $url = sprintf('%s://%s%s/api/v1/token/get', $this->scheme, $this->domain, $this->sufix);

        if ($this->logger) {
            $this->logger->info('Appsco.AppscoClient.getAccessData', array(
                'url' => $url,
                'code' => $code,
                'clientId' => $this->getClientId(),
                'client_secret' => $this->getClientSecret(),
                'redirect_uri' => $redirectUri,
                'accessToken' => $this->accessToken,
            ));
        }

        $oldAuthType = $this->getAuthType();
        $this->setAuthType(self::AUTH_TYPE_REQUEST);

        $json = $this->makeRequest(
            $url,
            'post',
            [],
            [
                'code' => $code,
                'redirect_uri' => $redirectUri,
            ]
        );

        $this->setAuthType($oldAuthType);

        if ($this->logger) {
            $this->logger->info('Appsco.AppscoClient.getAccessData', array(
                'result' => $json,
                'statusCode' => $this->httpClient->getStatusCode(),
            ));
        }

        if ($json === false || $this->httpClient->getStatusCode() != HttpStatusCode::OK) {
            throw new HttpException($this->httpClient->getStatusCode(), sprintf("%s\n%s\n%s\n%s",
                $url, $this->accessToken, $this->httpClient->getErrorText(), $json));
        }

        /** @var AccessData $result */
        $result = $this->serializer->deserialize($json, 'Appsco\Dashboard\ApiBundle\Model\AccessData', 'json');

        $this->setAccessToken($result->getAccessToken());

        return $result;
    }

    /**
     * @return Dashboard[]
     */
    public function getDashboardList()
    {
        $url = sprintf('%s://%s%s/api/v1/dashboard', $this->scheme, $this->domain, $this->sufix);

        if ($this->logger) {
            $this->logger->info('Appsco.AppscoClient.dashboardList', array(
                'url' => $url,
                'clientId' => $this->getClientId(),
                'client_secret' => $this->getClientSecret(),
              ));
        }

        $oldAuthType = $this->getAuthType();
        $this->setAuthType(self::AUTH_TYPE_ACCESS_TOKEN);

        $json = $this->makeRequest(
            $url,
            'get'
        );

        if ($this->logger) {
            $this->logger->info('Appsco.AppscoClient.dashboardList', array(
                'result' => $json,
                'statusCode' => $this->httpClient->getStatusCode(),
            ));
        }

        $this->setAuthType($oldAuthType);

        return $this->serializer->deserialize($json, 'array<Appsco\Dashboard\ApiBundle\Model\Dashboard>', 'json');
    }


    /**
     * @param string $id
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @return Account
     */
    public function profileRead($id = 'me')
    {
        $url = sprintf('%s://%s%s/api/v1/profile/%s', $this->scheme, $this->domain, $this->sufix, $id);

        if ($this->logger) {
            $this->logger->info('Appsco.AppscoClient.profileRead', array(
                'id' => $id,
                'url' => $url,
                'accessToken' => $this->accessToken,
            ));
        }

        $json = $this->makeRequest($url);

        if ($this->logger) {
            $this->logger->info('Appsco.AppscoClient.profileRead', array(
                'result' => $json,
                'statusCode' => $this->httpClient->getStatusCode(),
            ));
        }

        if ($json === false || $this->httpClient->getStatusCode() != HttpStatusCode::OK) {
            throw new HttpException($this->httpClient->getStatusCode(), sprintf("%s\n%s\n%s\n%s",
                $url, $this->accessToken, $this->httpClient->getErrorText(), $json));
        }

        return $this->serializer->deserialize($json, 'Appsco\Dashboard\ApiBundle\Model\Account', 'json');
    }

    /**
     * @param string $clientId
     * @return CertificateList
     */
    public function certificateGet($clientId)
    {
        $url = sprintf('%s://%s%s/api/v1/oauthapp/%s/certificates', $this->scheme, $this->domain, $this->sufix, $clientId);

        if ($this->logger) {
            $this->logger->info('Appsco.AppscoClient.certificateGet', array(
                'clientId' => $clientId,
                'url' => $url,
                'myId' => $this->getClientId(),
                'mySecret' => $this->getClientSecret(),
            ));
        }

        $old = $this->getAuthType();
        $this->setAuthType(self::AUTH_TYPE_REQUEST);
        $json = $this->makeRequest($url);
        $this->setAuthType($old);

        return $this->serializer->deserialize($json, 'Appsco\Dashboard\ApiBundle\Model\CertificateList', 'json');
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $queryData
     * @param array $postData
     * @param null $contentType
     * @param array $arrHeaders
     * @return string
     * @throws \LogicException
     * @throws HttpException
     */
    protected function makeRequest(
        $url,
        $method = null,
        array $queryData = array(),
        array $postData = array(),
        $contentType = null,
        array $arrHeaders = array()
    )
    {
        if (null == $method) {
            if ($this->authType == self::AUTH_TYPE_REQUEST) {
                $method = 'post';
            } else {
                $method = 'get';
            }
        }
        $this->prepareRequest($arrHeaders, $postData);
        switch($method){
            case 'post':
                $json = $this->httpClient->post($url, $queryData, $postData, $contentType, $arrHeaders);
                break;
            case 'get':
                $json = $this->httpClient->get($url, $queryData, $arrHeaders);
                break;
            case 'delete':
                $json = $this->httpClient->delete($url, $queryData, $arrHeaders);
                break;
            default:
                throw new \LogicException(sprintf("Unsupported HTTP method '%s'", $method));
        }

        if ($this->httpClient->getStatusCode() != HttpStatusCode::OK) {
            throw new HttpException($this->httpClient->getStatusCode(), $json);
        }

        return $json;
    }

    /**
     * @param $arrHeaders
     * @param $postData
     * @throws \RuntimeException
     * @throws \LogicException
     */
    private function prepareRequest(&$arrHeaders, &$postData)
    {
        switch($this->authType)
        {
            case self::AUTH_TYPE_ACCESS_TOKEN:
                if (false == $this->accessToken) {
                    throw new \RuntimeException('Access Token must be set');
                }
                $arrHeaders[] = 'Authorization: token '.$this->accessToken;
                break;

            case self::AUTH_TYPE_BASIC_AUTH:
                if (false == $this->clientId || false == $this->clientSecret) {
                    throw new \RuntimeException('ClientId and ClientSecret Must be set');
                }
                $this->httpClient->setCredentials($this->getClientId(), $this->getClientSecret());
                break;

            case self::AUTH_TYPE_REQUEST:
                if (false == $this->clientId || false == $this->clientSecret) {
                    throw new \RuntimeException('ClientId and ClientSecret Must be set');
                }
                $postData['client_id'] = $this->getClientId();
                $postData['client_secret'] = $this->getClientSecret();
                break;

            default:
                throw new \LogicException(sprintf("Invalid authentication type '%s'", $this->authType));
        }
    }

}