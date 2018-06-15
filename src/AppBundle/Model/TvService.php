<?php

namespace AppBundle\Model;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class TvService
{
    private $url;
    private $episode_repository;


    public function __construct($url, $repo =null)
    {
        $this->url = $url;
        $this->episode_repository = $repo;
    }

    public function getShows($start, $end)
    {
        $client = new Client();

        try {
            $response = $client->request(
                'GET',
                sprintf($this->url, $start->format('Y-m-d\\TH:i'), $end->format('Y-m-d\\TH:i'))
            );
            if ($response->getStatusCode() == 200) {
                return json_decode($response->getBody());
            } else {
                return [];
            }
        } catch (RequestException $e) {
            return [];
        }
    }

    public function getCurrent($shows = false, $numberOfNextEpisodes = 0)
    {
        $now = new \Datetime();
        if (!$shows) {
            $shows = $this->getShows(new \Datetime('-3 hours'), new \Datetime('+3 hours'));
        }

        $nowPlaying = [];
        $current = false;
        foreach ($shows as $show) {
            if (new \Datetime($show->airdate) <= $now) {
                $current = $show;
            } else {
                if ($numberOfNextEpisodes <= 0) {
                    break;
                }
                $nowPlaying[] = $show;
                $numberOfNextEpisodes--;
            }
        }
        array_unshift($nowPlaying, $current);
        return $nowPlaying;
    }
}
