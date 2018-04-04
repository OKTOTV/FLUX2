<?php

namespace AppBundle\Model;

use AppBundle\Entity\Episode;

class EpisodeService {

    private $notificator;
    private $logger;
    private $em;

    public function __construct($logger, $notificator, $em)
    {
        $this->notificator = $notificator;
        $this->logger = $logger;
        $this->em = $em;
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

}

 ?>
