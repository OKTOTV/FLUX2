<?php

namespace AppBundle\Model;

use AppBundle\Entity\Episode;

class SearchService
{
    private $episodeFinder;
    private $seriesFinder;
    private $playlistFinder;
    private $tagFinder;

    public function __construct($episodeFinder, $seriesFinder, $playlistFinder, $tagFinder)
    {
        $this->episodeFinder = $episodeFinder;
        $this->seriesFinder = $seriesFinder;
        $this->playlistFinder = $playlistFinder;
        $this->tagFinder = $tagFinder;
    }

    /**
     * Search Episodes
     * @param $searchphrase your searchphrase
     * @param $includeInactive if inactive episodes should be included
     * @return array of episodes
     */
    public function searchEpisodes($searchphrase, $includeInactive = false, $results = 5)
    {
        $boolQuery = new \Elastica\Query\BoolQuery();
        $multiquery = new \Elastica\Query\MultiMatch();
        $multiquery->setFields(['name', 'description', 'series']);
        $multiquery->setQuery($searchphrase);
        $multiquery->setType(\Elastica\Query\MultiMatch::TYPE_MOST_FIELDS);

        $boolQuery->addMust($multiquery);

        if (!$includeInactive) {
            $activeQuery = new \Elastica\Query\Term();
            $activeQuery->setTerm('is_active', true);
            $boolQuery->addMust($activeQuery);
        }
        return $this->episodeFinder->find($boolQuery, $results);
    }

    /**
     * Search Series
     * @param $searchphrase your searchphrase
     * @param $includeInactive if inactive episodes should be included
     * @return array of series
     */
    public function searchSeries($searchphrase, $includeInactive = false, $results = 5)
    {
        $boolQuery = new \Elastica\Query\BoolQuery();

        $query = new \Elastica\Query\Match();
        $query->setFieldQuery('name', $searchphrase);
        // $query->setFieldFuzziness('name', 0.7);
        // $query->setFieldMinimumShouldMatch('name', '40%');

        $boolQuery->addShould($query);

        $desc_query = new \Elastica\Query\Match();
        $desc_query->setFieldQuery('description', $searchphrase);
        // $desc_query->setFieldFuzziness('description', 0.7);

        $boolQuery->addShould($desc_query);

        if (!$includeInactive) {
            $activeQuery = new \Elastica\Query\Term();
            $activeQuery->setTerm('is_active', true);
            $boolQuery->addMust($activeQuery);
        }
        return $this->seriesFinder->find($boolQuery, $results);
    }

    public function searchPlaylists($searchphrase, $results = 5)
    {
        return $this->playlistFinder->find($searchphrase, $results);
    }

    public function searchRelatedEpisodes(Episode $episode, $numberResults = 5)
    {
        $tagtext = implode(" ", $episode->getTags()->toArray());

        $boolQuery = new \Elastica\Query\BoolQuery();
        $multiquery = new \Elastica\Query\MultiMatch();
        $multiquery->setFields(['name', 'description', 'series']);
        $multiquery->setQuery($tagtext);
        $multiquery->setType(\Elastica\Query\MultiMatch::TYPE_MOST_FIELDS);
        $boolQuery->addMust($multiquery);

        $activeQuery = new \Elastica\Query\Term();
        $activeQuery->setTerm('is_active', true);
        $boolQuery->addMust($activeQuery);

        $excludeEpisodeQuery = new \Elastica\Query\Term();
        $excludeEpisodeQuery->setTerm('id', $episode->getId());
        $boolQuery->addMustNot($excludeEpisodeQuery);

        return $this->episodeFinder->find($boolQuery, $numberResults);
    }

    public function searchTags($searchphrase)
    {
        $fieldQuery = new \Elastica\Query\Match();
        $fieldQuery->setFieldQuery('slug', $searchphrase);
        $fieldQuery->setFieldParam('slug', 'analyzer', 'my_analyzer');
        return $this->tagFinder->find($fieldQuery);
    }
}

 ?>
