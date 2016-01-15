<?php

namespace MediaBundle\Controller;

use Oktolab\MediaBundle\Controller\SeriesController as BaseController;
use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Series;
use MediaBundle\Form\EpisodeType;

/**
 * @Route("/oktolab_media/series")
 */
class SeriesController extends BaseController
{
    /**
     * Finds and displays a Series entity.
     * @ParamConverter("series", class="AppBundle:Series")
     * @Route("/show/{series}", name="oktolab_series_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($series)
    {
        return ['series' => $series];
    }

    /**
     * Finds and displays a Series entity.
     * @ParamConverter("series", class="AppBundle:Series")
     * @Route("/{series/paginate/{series}/{page}", name="media_episode_paginator", requirements={"page": "\d+"}, defaults={"page":1})
     * @Method("GET")
     * @Template()
     */
    public function paginationEpisodesAction($series, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT e FROM ".$this->container->getParameter('oktolab_media.episode_class')." e WHERE e.series = :series";
        $query = $em->createQuery($dql);
        $query->setParameter('series', $series->getId());

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page/*page number*/,
            2
        );
        $pagination->setUsedRoute('media_episode_paginator', ['series' => $series]);
        $pagination->setParam('series', $series->getId());

        return ['pagination' => $pagination];
    }
}
