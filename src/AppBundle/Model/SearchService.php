<?php

namespace AppBundle\Model;

class SearchService
{
    private $episodeFinder;
    private $seriesFinder;
    private $playlistFinder;

    public function __construct($episodeFinder, $seriesFinder, $playlistFinder)
    {
        $this->episodeFinder = $episodeFinder;
        $this->seriesFinder = $seriesFinder;
        $this->playlistFinder = $playlistFinder;
    }

    /**
     * Search Episodes
     * @param $searchphrase your searchphrase
     * @param $includeInactive if inactive episodes should be included
     * @return array of episodes
     */
    public function searchEpisodes($searchphrase, $includeInactive = false)
    {
        $boolQuery = new \Elastica\Query\BoolQuery();

        $query = new \Elastica\Query\Match();
        $query->setFieldQuery('name', $searchphrase);
        $query->setFieldFuzziness('name', 0.7);
        $query->setFieldMinimumShouldMatch('name', '40%');

        $boolQuery->addShould($query);

        $desc_query = new \Elastica\Query\Match();
        $desc_query->setFieldQuery('description', $searchphrase);
        $desc_query->setFieldFuzziness('description', 0.7);
        $desc_query->setFieldMinimumShouldMatch('description', '40%');

        $boolQuery->addShould($desc_query);

        if ($includeInactive) {
            $activeQuery = new \Elastica\Query\Term();
            $activeQuery->setTerm('is_active', true);
            $boolQuery->addMust($activeQuery);
        }
        return $this->episodeFinder->find($boolQuery);
    }

    /**
     * Search Series
     * @param $searchphrase your searchphrase
     * @param $includeInactive if inactive episodes should be included
     * @return array of series
     */
    public function searchSeries($searchphrase, $includeInactive = false)
    {
        $boolQuery = new \Elastica\Query\BoolQuery();

        $query = new \Elastica\Query\Match();
        $query->setFieldQuery('name', $searchphrase);
        $query->setFieldFuzziness('name', 0.7);
        $query->setFieldMinimumShouldMatch('name', '40%');

        $boolQuery->addShould($query);

        $desc_query = new \Elastica\Query\Match();
        $desc_query->setFieldQuery('description', $searchphrase);
        $desc_query->setFieldFuzziness('description', 0.7);

        $boolQuery->addShould($desc_query);

        if ($includeInactive) {
            $activeQuery = new \Elastica\Query\Term();
            $activeQuery->setTerm('is_active', true);
            $boolQuery->addMust($activeQuery);
        }
        return $this->seriesFinder->find($boolQuery);
    }

    public function searchPlaylists($searchphrase)
    {
        return $this->playlistFinder->find($searchphrase);
    }
}

 ?>
