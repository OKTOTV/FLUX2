<?php

namespace AppBundle\Model;

use AppBundle\Entity\Episode;

class EpisodeService {

    private $notificator;
    private $logger;
    private $em;
    private $bprs_analytics;

    public function __construct($logger, $notificator, $em, $bprs_analytics)
    {
        $this->notificator = $notificator;
        $this->logger = $logger;
        $this->em = $em;
        $this->bprs_analytics = $bprs_analytics;
    }

    public function publishEpisode(Episode $episode)
    {
        if ($episode->getTechnicalStatus() >= Episode::STATE_READY) {
            // $this->logger->info();
            $episode->setIsActive(true);
            $episode->setOnlineStart(new \Datetime());
            $this->em->persist($episode);
            $this->em->flush();
            $this->notificator->onNewEpisode($episode);
            return true;
        }
        // can't publish episode if not ready
        return false;
    }

    public function updateTrendingScore($numberEpisodes = 60)
    {
        // update the top 60 episodes with their respective viewcounts;
        $query = $this->em->createQuery(
            "SELECT e.id, COUNT(l) AS viewCount
            FROM AppBundle:Episode e
            JOIN e.series s
            JOIN BprsAnalyticsBundle:Logstate l WITH l.identifier = e.uniqID
            WHERE e.isActive = 1
            AND s.isActive = 1
            AND e.onlineStart < :now
            AND l.value = :value
            AND l.timestamp > :range
            GROUP By e.id
            ORDER BY viewCount DESC"
            )->setParameter('now', new \DateTime())
            ->setParameter('value', '20%')
            ->setParameter('range', new \DateTime('-7 days'));

        $scores = $query
            ->setMaxResults($numberEpisodes)
            ->getScalarResult();

        // reset all episode scores to 0
        $query = $this->em->createQuery(
            "Update AppBundle:Episode e set e.trending_score = 0"
            )->getResult();

        foreach ($scores as $score) {
            $query = $this->em->createQuery(
                "Update AppBundle:Episode e set e.trending_score = :score WHERE e.id = :id"
                )
                ->setParameter('score', $score['viewCount'])
                ->setParameter('id', $score['id'])
                ->getResult();
        }
    }

    public function getClicksInTimerange($startdate, $enddate)
    {
        $results = [];
        $q = $this->em->createQuery("SELECT e.name as episodename, e.views, e.uniqID, s.name as seriesname FROM AppBundle:Episode e LEFT JOIN e.series s");
        $iterableResult = $q->iterate();
        $logstates0 = $this->bprs_analytics->getLogstatesInTime(['value' => 'start'], $startdate, $enddate);
        $logstates = $this->bprs_analytics->getLogstatesInTime(['value' => '20%'], $startdate, $enddate);
        $logstates40 = $this->bprs_analytics->getLogstatesInTime(['value' => '40%'], $startdate, $enddate);
        $logstates60 = $this->bprs_analytics->getLogstatesInTime(['value' => '60%'], $startdate, $enddate);
        $logstates80 = $this->bprs_analytics->getLogstatesInTime(['value' => '80%'], $startdate, $enddate);
        $logstatesEnd = $this->bprs_analytics->getLogstatesInTime(['value' => 'end'], $startdate, $enddate);
        $i = 0;
        while (($row = $iterableResult->next()) !== false) {
            $results[$row[$i]['uniqID']]['episode'] = $row[$i]['episodename'];
            $results[$row[$i]['uniqID']]['series'] = $row[$i]['seriesname'];
            $results[$row[$i]['uniqID']]['clicks'] = $row[$i]['views'];
            $zero_percent = 0;
            $twenty_percent = 0;
            $fourty_percent = 0;
            $sixty_percent = 0;
            $eighty_percent = 0;
            $end = 0;

            foreach ($logstates0 as $logstate) {
                if ($logstate->getIdentifier() == $row[$i]['uniqID']) {
                    $zero_percent++;
                    unset($logstate);
                }
            }

            foreach ($logstates as $logstate) {
                if ($logstate->getIdentifier() == $row[$i]['uniqID']) {
                    $twenty_percent++;
                    unset($logstate);
                }
            }
            foreach ($logstates40 as $logstate) {
                if ($logstate->getIdentifier() == $row[$i]['uniqID']) {
                    $fourty_percent++;
                    unset($logstate);
                }
            }
            foreach ($logstates60 as $logstate) {
                if ($logstate->getIdentifier() == $row[$i]['uniqID']) {
                    $sixty_percent++;
                    unset($logstate);
                }
            }
            foreach ($logstates80 as $logstate) {
                if ($logstate->getIdentifier() == $row[$i]['uniqID']) {
                    $eighty_percent++;
                    unset($logstate);
                }
            }
            foreach ($logstatesEnd as $logstate) {
                if ($logstate->getIdentifier() == $row[$i]['uniqID']) {
                    $end++;
                    unset($logstate);
                }
            }
            $results[$row[$i]['uniqID']]['0'] = $zero_percent;
            $results[$row[$i]['uniqID']]['20'] = $twenty_percent;
            $results[$row[$i]['uniqID']]['40'] = $fourty_percent;
            $results[$row[$i]['uniqID']]['60'] = $sixty_percent;
            $results[$row[$i]['uniqID']]['80'] = $eighty_percent;
            $results[$row[$i]['uniqID']]['end'] = $end;
            $this->em->clear();
            $i++;
        }

        return $results;
    }

}

 ?>
