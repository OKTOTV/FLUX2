<?php

namespace Bprs\AnalyticsBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * LogstateRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LogstateRepository extends EntityRepository
{
    public function getNumberOfRefreshes($client_ip, $url, $from = false, $to = false)
    {
        if (!$from) {
            $from = new \Datetime('-5 minutes');
        }

        if (!$to) {
            $to = new \Datetime();
        }
        return $this->getEntityManager()
            ->createQuery('SELECT COUNT(l) FROM BprsAnalyticsBundle:Logstate l WHERE l.url = :url AND (l.timestamp >= :start_time AND l.timestamp <= :end_time)  AND l.clientIp = :client_ip')
            ->setParameter('url', $url)
            ->setParameter('start_time', $from)
            ->setParameter('end_time', $to)
            ->setParameter('client_ip', $client_ip)
            ->getSingleScalarResult();
    }

    /**
     * returns Logstates for given key => value Infos
     */
    public function getLogstatesForValues(array $values)
    {
        $where = "";
        $first = true;
        foreach ($values as $key => $value) {
            if ($first) {
                $where = sprintf("v.%s = :%s", $key, $value);
                $first = false;
            } else {
                $where = sprintf("%s AND v.%s = :%s", $where, $key, $value);
            }
        }

        $query = $this->getEntityManager()
            ->createQuery('SELECT l FROM BprsAnalyticsBundle:Logstate l JOIN l.values v WHERE '.$where);

        foreach ($values as $key => $value) {
            $query->setParameter($key, $value);
        }

        return $query->getResult();
    }

    /**
     * returns Logstates in timerange for given logstate key => values. (similar to findBy())
     */
    public function getLogstatesInTimeForValues(array $values, $from = false, $to = false)
    {
        if (!$from) {
            $from = new \Datetime('-1 day');
        }

        if (!$to) {
            $to = new \Datetime();
        }

        $where = "";
        $first = true;
        foreach ($values as $key => $value) {
            if ($first) {
                $where = sprintf("v.key = :%s_key AND v.value = :%s_value", $key, $key);
                $first = false;
            } else {
                $where = sprintf("%s AND v.%s = :%s", $where, $key, $key);
            }
        }
        // die(var_dump($where));
        $query = $this->getEntityManager()
            ->createQuery('SELECT l FROM BprsAnalyticsBundle:Logstate l LEFT JOIN l.values v WHERE (l.timestamp >= :start_time AND l.timestamp <= :end_time) AND '.$where.' ORDER BY l.timestamp ASC')
            ->setParameter('start_time', $from)
            ->setParameter('end_time', $to);

        foreach ($values as $key => $value) {
            $query->setParameter($key.'_key', $key);
            $query->setParameter($key.'_value', $value);
        }

        return $query->getResult();
    }

    public function getLogstatesInTimeWithoutValues(array $values, $from = false, $to = false)
    {
        if (!$from) {
            $from = new \Datetime('-1 day');
        }

        if (!$to) {
            $to = new \Datetime();
        }

        $where = "";
        $first = true;
        foreach ($values as $key => $value) {
            if ($first) {
                $where = sprintf("v.%s = :%s", $key, $value);
                $first = false;
            } else {
                $where = sprintf("%s AND v.%s = :%s", $where, $key, $value);
            }
        }

        $query = $this->getEntityManager()
            ->createQuery('SELECT l FROM BprsAnalyticsBundle:Logstate l WHERE (l.timestamp >= :start_time AND l.timestamp <= :end_time) AND SIZE(l.values) = 0')
            ->setParameter('start_time', $from)
            ->setParameter('ent_time', $to);

        foreach ($values as $key => $value) {
            $query->setParameter($key, $value);
        }

        return $query->getResult();

    }
}
