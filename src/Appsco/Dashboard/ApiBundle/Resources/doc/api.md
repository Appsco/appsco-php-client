Appsco API Methods
==================

The Appsco API Bundle provides the PHP implementation of the client in the class

    Appsco\Dashboard\ApiBundle\Client\AppscoClient

If you loaded the AppscoDashboardApiBundle to your kernel you can get it as a service from the container

``` php
/** @var \Appsco\Dashboard\ApiBundle\Client\AppscoClient $client */
$client = $this->get('appsco_dashboard_api.client');
```



Profile Read
------------

Returns profile info for specified user.

    GET https://my.dev.appsco.com/api/v1/profile/:id

Parameters
 * :id - the Appsco ID of the user, or 'me' as alias to the user that gave the authorization

Response
``` json
{
    "id": 123,
    "email": "john.smith@example.com",
    "first_name": "John",
    "last_name": "Smith",
    "locale": "en",
    "timezone": "Europe/Oslo",
    "gender": "m",
    "country": "NO",
    "phone": "00123123123",
    "picture_url": "https://my.dev.appsco.com/picture/123"
}
```

Response of this API method is implemented by class

    Appsco\Dashboard\ApiBundle\Model\Account

and the client class method is

``` php
    /**
     * @param string $id
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @return \Appsco\Dashboard\ApiBundle\Model\Account
     */
    public function profileRead($id = 'me');
```



Certificate get
---------------

Returns X509 certificates for specified OAuth application registered at Appsco.

    GET https://my.dev.appsco.com/api/v1/oauthapp/:client_id/certificates

Parameters:

 * :client_id - the Client ID of the application

Response:

``` json
{
    "client_id": "376i4gytwe0w0wcc84s4ko8o4o0o4ososkk0sskwskc8o4ssgo",
    "owner_id": 123,
    "certificates": [
        {
            "valid_from": "2014-07-02",
            "valid_to": "2015-07-01",
            "fingerprint": "af43b1c833de6f1c83f43",
            "certificate": "-----BEGIN CERTIFICATE-----\nMIIEZDCCA0ygAwIBAgIBADANB..."
        }
    ]
}
```

Response of this API method is implemented by class

    Appsco\Dashboard\ApiBundle\Model\CertificateList

and the client class method is

``` php
    /**
     * @param string $clientId
     * @return CertificateList
     */
    public function certificateGet($clientId);
```

