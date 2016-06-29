<?php

namespace MediaBundle\Controller;

use Oktolab\MediaBundle\Controller\SeriesController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use MediaBundle\Entity\Series;
use MediaBundle\Form\EpisodeType;
use MediaBundle\Form\SeriesUserType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @Route("/oktolab_media/series")
 */
class SeriesController extends BaseController
{
    /**
     * Finds and displays a Series entity.
     * @ParamConverter("series", class="MediaBundle:Series")
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
     * @ParamConverter("series", class="MediaBundle:Series")
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
            5
        );
        $pagination->setUsedRoute('media_episode_paginator', ['series' => $series]);
        $pagination->setParam('series', $series->getId());

        return ['pagination' => $pagination];
    }

    /**
     * @Route("/{series}/edit_user", name="oktolab_series_edit_user")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editUserAction(Request $request, Series $series)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:User');
        $form = $this->createForm(new SeriesUserType($repo), $series);
        $form->add('submit', SubmitType::class, ['label' => 'flux2.series_edit_user_button', 'attr' => ['class' => 'btn btn-default']]);

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($series);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'flux2.series_edit_user_success');
                return $this->redirect($this->generateUrl('oktolab_series_show', ['series' => $series->getId()]));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'flux2.series_edit_user_error');
            }
        }
        return ['form' => $form->createView(), 'series' => $series];
    }

    /**
     * @Route("/user_by_username", name="oktolab_series_user_by_username")
     */
    public function ajaxUserByUsernameAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $users = $em->getRepository('AppBundle:User')->findAll();
            $json = [];
            foreach ($users as $user) {
                $json[] = $user->getUsername();
            }
            return new Response(json_encode($json));
        }
        return $this->redirect('homepage');
    }
}
