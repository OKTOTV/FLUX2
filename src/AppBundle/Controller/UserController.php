<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\User;
use AppBundle\Form\RegisterType;

/**
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/playlists/{page}", name="user_playlists", requirements={"page": "\d+"}, defaults={"page": 1})
     * @Template()
     */
    public function playlistsAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $playlists = $paginator->paginate($em->getRepository('AppBundle:User')->findPlaylistsQuery($this->getUser()), $page, 10);

        return ['playlists' => $playlists];
    }

    /**
     * @Route("/favorites", name="user_favorites", requirements={"page": "\d+"}, defaults={"page": 1})
     * @Template()
     */
    public function favoritesAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $favorites = $paginator->paginate($em->getRepository('AppBundle:User')->findFavoritesQuery($this->getUser()), $page, 10);
        return ['favorites' => $favorites];
    }

    /**
     * @Route("/channels", name="user_channels", requirements={"page": "\d+"}, defaults={"page": 1})
     * @Template()
     */
    public function channelsAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $channels = $paginator->paginate($em->getRepository('AppBundle:User')->findChannelsQuery($this->getUser()), $page, 10);
        return ['channels' => $channels];
    }

    /**
     * @Route("/addFavorite", name="backend_add_favourite")
     * @deprecated use updateFavorite
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
     * @deprecated use updateFavorite
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

    /**
     * @Route("/updateFavorite", name="user_update_favorite")
     */
    public function updateFavoriteAciton(Request $request)
    {
        $uniqID = $request->request->get('uniqID');
        if ($uniqID) {
            $this->get('user_service')->updateFavorite($this->getUser(), $uniqID);
            return new Response('', Response::HTTP_OK);
        }
        return new Response('', Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/updateSubscription", name="user_update_subscription")
     */
    public function updateSubscriptionAction(Request $request)
    {
        $uniqID = $request->request->get('uniqID');
        if ($uniqID) {
            $this->get('user_service')->updateSubscription($this->getUser(), $uniqID);
            return new Response('', Response::HTTP_OK);
        }
        return new Response('', Response::HTTP_BAD_REQUEST);
    }
}
