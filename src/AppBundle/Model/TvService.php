<?php

namespace AppBundle\Model;

use GuzzleHttp\Client;

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

        $response = $client->request(
            'GET',
            sprintf($this->url, $start->format('Y-m-d\\TH:i'), $end->format('Y-m-d\\TH:i'))
        );
        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody());
            $shows = json_decode($response->getBody());

            $uniqIDs = [];
            foreach ($shows as $show) {
                $uniqIDs[] = $show->id;
            }
            $episodes = $this->episode_repository->findEpisodesByUniqID($uniqIDs);

        } else {
            return [];
        }
    }

    public function getCurrent($shows = false)
    {
        $now = new \Datetime();
        $current = false;
        if (!$shows) {
            $shows = $this->getShows($now, new \Datetime('+1 day'));
        }

        foreach ($shows as $show) {
            if ($show->airdate <= $now) {
                $current = $show;
            } else {
                break;
            }
        }
        return $current;
    }
}
