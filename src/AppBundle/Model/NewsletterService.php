<?php

namespace AppBundle\Model;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class NewsletterService {

    private $api_key;
    private $default_newsletter;

    public function __construct($apiKey, $default_newsletter)
    {
        $this->api_key = $apiKey;
        $this->default_newsletter = $default_newsletter;
    }

    public function subscribe($email)
    {
        $checksum = md5($email);
        $url = sprintf('https://us7.api.mailchimp.com/3.0/lists/%s/members/%s', $this->default_newsletter, $checksum);
        $client = new Client();
        try {
            $response = $client->request('GET', $url, ['auth' => ['anystring', $this->api_key]]);
            switch ($response->getStatusCode()) {
                case 200:
                    // update user, set status to subscribed
                    $response = $client->request(
                        'PATCH',
                        $url,
                        [
                            'auth' => ['anystring', $this->api_key],
                            'json' => [
                                'status' => 'pending'
                            ]
                        ]
                    );
                    return true;
                    break;
            }
        } catch (RequestException $e) {
            switch ($e->getResponse()->getStatusCode()) {
                case 404:
                    // no subscriber found, subscribe now
                    $response = $client->request(
                        'POST',
                        sprintf('https://us7.api.mailchimp.com/3.0/lists/%s/members', $this->default_newsletter),
                        [
                            'auth' => ['anystring', $this->api_key],
                            'json' => [
                                'email_address' => $email,
                                'status' => 'pending'
                            ]
                        ]
                    );
                    return true;
                    break;
                default:
                    return false;
            }
        }
    }
}
