<?php

namespace AppBundle\Model;

class SearchService
{
    private $episodeFinder;
    private $seriesFinder;
    private $playlistFinder;
    private $postFinder;
    private $pageFinder;

    public function __construct($episodeFinder, $seriesFinder, $pageFinder)
    {
        $this->episodeFinder = $episodeFinder;
        $this->seriesFinder = $seriesFinder;
        $this->pageFinder = $pageFinder;
        // $this->playlistFinder = $playlistFinder;
        // $this->postFinder = $postFinder;
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

    /**
     * Search Pages
     * @param $searchphrase your searchphrase
     * @param $includeInactive if inactive episodes should be included
     * @return array of pages
     */
    public function searchPages($searchphrase, $includeInactive = false)
    {
        $boolQuery = new \Elastica\Query\BoolQuery();

        $query = new \Elastica\Query\Match();
        $query->setFieldQuery('title', $searchphrase);
        $query->setFieldFuzziness('title', 0.7);
        $query->setFieldMinimumShouldMatch('title', '40%');

        $boolQuery->addShould($query);

        $desc_query = new \Elastica\Query\Match();
        $desc_query->setFieldQuery('text', $searchphrase);
        $desc_query->setFieldFuzziness('text', 0.7);

        $boolQuery->addShould($desc_query);

        if ($includeInactive) {
            $activeQuery = new \Elastica\Query\Term();
            $activeQuery->setTerm('is_active', true);
            $boolQuery->addMust($activeQuery);
        }
        return $this->pageFinder->find($boolQuery);
    }
}

 ?>
