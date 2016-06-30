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
            // @TODO: test check for episodes and set uniqIDs to shows
            // $shows = json_decode($response->getBody());
            //
            // $uniqIDs = [];
            // foreach ($shows as $show) {
            //     $uniqIDs[] = $show->id;
            // }
            // $episodes = $this->episode_repository->findEpisodesByUniqID($uniqIDs);

        } else {
            return [];
        }
    }

    public function getCurrent($shows = false)
    {
        $now = new \Datetime();
        $current = false;
        if (!$shows) {
            $shows = $this->getShows(new \Datetime('-3 hours'), new \Datetime('+3 hours'));
        }

        foreach ($shows as $show) {
            if (new \Datetime($show->airdate) <= $now) {
                $current = $show;
            } else {
                break;
            }
        }
        return $current;
    }
}
