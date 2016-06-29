<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Abonnement;
use AppBundle\Form\AbonnementType;
use AppBundle\Entity\Notification;
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
        $channels = $paginator->paginate($em->getRepository('AppBundle:User')->findAbonnementsQuery($this->getUser()), $page, 10);
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
            $episode = $em->getRepository('MediaBundle:Episode')->findOneBy(array('uniqID' => $uniqID));
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
            $episode = $em->getRepository('MediaBundle:Episode')->findOneBy(array('uniqID' => $uniqID));
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

    /**
     * @Route("/updateAbonnement/{abonnement}", name="user_update_abonnement")
     * @Template()
     */
    public function updateAbonnementAction(Request $request, Abonnement $abonnement)
    {
        $this->denyAccessUnlessGranted('view', $abonnement); //symfony voter

        $form = $this->createForm(new AbonnementType(), $abonnement);
        $form->add('submit', 'submit', ['label' => 'oktothek.user_update_abonnement_button', 'attr' => ['class' => 'btn btn-primary']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($post);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'oktothek.success_edit_abonnement');

                return $this->redirect($this->generateUrl('user_channels'));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_edit_post');
            }
        }

        return ['form' => $form->createView(), 'series' => $abonnement->getSeries()];
    }

    /**
     * @Route("/notification/{notification}", name="user_notification")
     */
    public function showNotificationAction(Notification $notification)
    {
        // TODO: notification voter!
        $em = $this->getDoctrine()->getManager();
        $em->remove($notification);
        $em->flush();
        switch ($notification->getType()) {
            case Notification::NEW_POST:
                return $this->redirect($this->generateUrl('oktothek_show_series', ['uniqID' => $notification->getSeries()->getUniqID()]));
                break;
            case Notification::NEW_EPISODE:
                //TODO: return redirect to newest series episode (maybe playlist?)
                return $this->redirect($this->generateUrl('oktothek_show_series', ['uniqID' => $notification->getSeries()->getUniqID()]));
                break;
            case Notification::LIVESTREAM:
                return $this->redirect($this->generateUrl('tv'));
                break;
        }
    }
}
