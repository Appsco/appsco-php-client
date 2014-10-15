Appsco OAuth v2
===============

OAuth2 is a protocol that lets applications request authorization to details of Appsco user's account
without getting their passwords. Applications using granted authorization tokens can call Appsco API
and impersonate users that gave the authorization for the given scope. Users can revoke given access token
at any time.

All applications has to be registered on [Appsco](https://my-dev.appsco.com) in order to be able
to use its API. Each registered app is given a unique Client ID and Client Secret. The Client Secret should not be
shared and given to any third parties.

![oauth](http://appsco.github.io/appsco-php-client/oauth.svg "OAuth")

Reference
---------

 * [IETF OAuth 2.0 WG specification](http://tools.ietf.org/html/rfc6749)
 * [OAuth OpenID Connect Core](http://openid.net/specs/openid-connect-core-1_0.html)
 * [Wikipedia](http://en.wikipedia.org/wiki/OAuth)


Web Application Flow
====================

Following is a description of the OAuth2 for web applications.

1. Redirect users to request Appsco access
------------------------------------------

    GET https://my-dev.appsco.com/oauth/authorize


Parameters
----------

| Name            | Type        | Description
| --------------- | ------------ | ------------
| `client_id`     | `string`     | **Required** The Client ID you received when you registered the app
| `redirect_uri`  | `string`     | The URL in your app where users will be sent after authorization. See details below about [redirect urls](#redirect-urls).
| `scope`         | `string`     | Space separated list of [scopes](#scopes). If not provided defaults to `profile_read`
| `state`         | `string`     | An unguessable random string. It is used to protect against cross-site request forgery attacks.


2. Appsco redirects back to your site
-------------------------------------

If the user accepted your request and gave you the authorization for requested scope, Appsco redirects back
to your site with a temporary code in the `code` parameter together with the state you provided in the previous request
in the `state` parameter. If the state does not match, the request is made by third party and the process should be
aborted.

Exchange the temporary code for access token.

    POST https://my-dev.appsco.com/api/v1/token/get

Parameters
----------

| Name                | Type        | Description
| ------------------- | ------------ | ------------
| `client_id`         | `string`     | **Required** The Client ID you received when you registered the app
| `client_secret`     | `string`     | **Required** The Client Secret you received when you registered the app
| `code`              | `string`     | The code you received as a response to previous request
| `redirect_uri`      | `string`     | The URL in your app where users will be sent after authorization. See details below about [redirect urls](#redirect-urls).

The response will take the following form

``` json
{
    "access_token": "2z0z3okj0ha8skg400wo440ogwssg0k8csg4wogcw4w0ckggow",
    "scope": "profile_read",
    "token_type": "bearer",
    "id_token": "d8r781oclb4gg4wwc0wc8c848wc4ok4kc400wks4o0cs88gcou9lhwjkc3msws0g8c4osgc0kw8ckkcwwws4gwocwgowsgk4g8"
}
```

3. Use the access token to access the API
-----------------------------------------

The obtained access token allows you to make requests to the Appsco API on behalf of the user that gave
you the authorization.

    GET https://my-dev.appsco.com/api/v1/profile/me?access_token=...

You can pass the token as the query param as shown above, but safer and cleaner way is to include it in the
Authorization header

    Authorization: token THE-OAUTH-ACCESS-TOKEN

For example, with curl you can set the Authorization header with following command:

    curl -H "Authorization: token THE-OAUTH-ACCESS-TOKEN" https://my-dev.appsco.com/api/v1/profile/me


Appsco OAuth Client
-------------------

The Appsco API Bundle implements OAuth client that automates the steps described above.

``` php
<?php

namespace AcmeBundle;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function startAction()
    {
        return $this->getAppscoOAuth()->start();
    }

    public function callbackAction(Request $request)
    {
        $token = $this->getAppscoOAuth()->callback($request);
        return new Response("Hello {$token->getUser()->getEmail()}!");
    }

    /**
     * @return \Appsco\Dashboard\ApiBundle\OAuth\AppscoOAuth
     */
    private function getAppscoOAuth()
    {
        return $this->get('appsco_dashboard_api.oauth');
    }

}

```

For the profile object filed reference check the [Profile Read API method](api.md#Profile-Read) documentation.

**Note:** The code above does not login that user into your Symfony application security context. It just returns the
`Token` object. In order to login such user and the returned `Token` Symfony authentication listener has to be
registered and used. Appsco API Bundle implements a security factory with an authentication listener, for details
see [Getting started](getting-started.md) and [Configuration reference](configuration.md).



Redirect URLs
=============

The `redirect_uri` parameter is optional. If left out, Appsco will redirect users to the redirect URL
configured in the oauth application settings. If provided, the redirect URL’s host and port must exactly match the
callback URL. The redirect URL’s path must reference a subdirectory of the callback URL.


Scopes
======

| Name                | Description
| ------------------- | ------------
| `profile_read`      | Grants read-only access to profile information


Errors
======

In case something went wrong during the OAuth process instead of expected parameters Appsco will redirect
with the `error` and `error_description` parameters describing the error. Following are the common errors that might
occur.

Application Suspended
---------------------

If your application has been suspended (due to reported abuse, spam, or a mis-use of the API), Appsco will
redirect to the registered callback URL with following parameters:

   https://your-application.com/appsco-callback?error=application_suspended
       &error_description=Your+application+has+been+suspended.+Contact+support@appsco.com
       &state=123

Redirect URI mismatch
---------------------

If you provide a `redirect_uri` that does not match with the data from registered application, Appsco will
redirect to the registered callback URL with following parameters:

   https://your-application.com/appsco-callback?error=redirect_uri_mismatch
       &error_description=The+redirect_uri+MUST+match+the+registered+callback+URL+for+this+application.
       &state=123


Access denied
-------------

If the user rejects access to your application, Appsco will redirect to the registered callback URL
with following parameters:

   https://your-application.com/appsco-callback?error=access_denied
       &error_description=The+user+has+denied+your+application+access.
       &state=123


Incorrect client credentials
----------------------------

If provided `client_id` and `client_secret` does not match to those of the registered application, you will receive
this error response:

``` json
{
    "error": "incorrect_client_credentials",
    "error_description": "The client_id and/or client_secret passed are incorrect."
}
```


Bad verification code
---------------------

If the temporary verification code passed in request to obtain access token is invalid or expired, you will receive
this error response:

``` json
{
    "error": "bad_verification_code",
    "error_description": "The code passed is incorrect or expired."
}
```
