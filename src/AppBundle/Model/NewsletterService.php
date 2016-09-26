<?php

namespace AppBundle\Model;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class NewsletterService {

    private $api_key;

    public function __construct($apiKey)
    {
        $this->api_key = $apiKey;
    }

    public function subscribe($email, $newsletter)
    {
        $checksum = md5($email);
        $url = sprintf('https://us7.api.mailchimp.com/3.0/lists/%s/members/%s', $newsletter, $checksum);
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
                                'status' => 'subscribed'
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
                        sprintf('https://us7.api.mailchimp.com/3.0/lists/%s/members', $newsletter),
                        [
                            'auth' => ['anystring', $this->api_key],
                            'json' => [
                                'email_address' => $email,
                                'status' => 'subscribed'
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
