<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
/**
 * @Route("/backend")
 */
class BackendController extends Controller
{
    /**
     * @Route("/", name="backend")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        return array();
    }

    /**
     * @Route("/keychains", name="keychains")
     * @Template()
     */
    public function keychainAction(Request $request)
    {
        return array();
    }

    /**
     * @Route("/addFavorite", name="backend_add_favourite")
     */
    public function addFavoriteAction(Request $request)
    {
        $uniqID = $request->request->get('uniqID');
        $type = $request->request->get('type');
        if ($uniqID && $type) {
            // TODO: create service
            $em = $this->getDoctrine()->getManager();
            $episode = $em->getRepository('AppBundle:Episode')->findOneBy(array('uniqID' => $uniqID));
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $user->addFavorite($episode);
            $em->persist($user);
            $em->flush();

            return new Response('', Response::HTTP_OK);
        }
        return new Response('', Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/removeFavorite", name="backend_remove_favourite")
     */
    public function removeFavoriteAction(Request $request)
    {
        $uniqID = $request->request->get('uniqID');
        $type = $request->request->get('type');
        if ($uniqID && $type) {
            // TODO: create service
            $em = $this->getDoctrine()->getManager();
            $episode = $em->getRepository('AppBundle:Episode')->findOneBy(array('uniqID' => $uniqID));
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $user->removeFavorite($episode);
            $em->persist($user);
            $em->flush();

            return new Response('', Response::HTTP_SUCCESS);
        }
        return new Response('', Response::HTTP_BAD_REQUEST);
    }
}