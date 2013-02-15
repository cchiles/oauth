<?php
/**
 * OAuth Tumblr Provider
 *
 * Documents for implementing Tumblr OAuth can be found at
 * <http://tumblr.com/api>.
 *
 * [!!] This class does not implement the Tumblr API. It is only an
 * implementation of standard OAuth with Tumblr as the service provider.
 *
 * @package    OAuth
 * @category   Provider
 * @author     Fuel Development Team
 */

namespace OAuth\Provider;

class Tumblr extends \OAuth\OAuth1\Provider
{
    public $name = 'tumblr';

    public function requestTokenUrl()
    {
        return 'http://www.tumblr.com/oauth/request_token';
    }

    public function authorizeUrl()
    {
        return 'http://www.tumblr.com/oauth/authorize';
    }

    public function accessTokenUrl()
    {
        return 'http://www.tumblr.com/oauth/access_token';
    }

    public function getUserInfo(OAuth_Consumer $consumer, OAuth_Token $token)
    {
        // Create a new GET request with the required parameters
        $request = OAuth_Request::forge('resource', 'GET', 'http://api.tumblr.com/v2/user/info', array(
            'oauth_consumer_key' => $consumer->key,
            'oauth_token' => $token->access_token,
        ));

        // Sign the request using the consumer and token
        $request->sign($this->signature, $consumer, $token);

        $response = json_decode($request->execute());

        $status = current($response);
        $response = next($response);
        $user = $response->user;

        // Create a response from the request
        return array(
            'uid' => $user->name,    // Tumblr doesn't provide a unique key other than name
            'name' => $user->name,
            'likes' => $user->likes,
            'following' => $user->following,
            'default_post_format' => $user->default_post_format,
        );
    }

} // End Provider_Tumblr
