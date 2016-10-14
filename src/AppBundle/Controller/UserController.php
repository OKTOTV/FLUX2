<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Abonnement;
use AppBundle\Form\AbonnementType;
use AppBundle\Entity\Notification;
use AppBundle\Entity\User;

/**
 * @Route("/user")
 * @Security("has_role('ROLE_OKTOLAB_USER')")
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
     * @Route("/updateFavorite", name="user_update_favorite")
     */
    public function updateFavoriteAciton(Request $request)
    {
        $uniqID = $request->request->get('uniqID');
        if ($uniqID) {
            $count = $this->get('user_service')->updateFavorite($this->getUser(), $uniqID);
            return new JsonResponse(['favorites' => $count], Response::HTTP_OK);
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
                $em->persist($abonnement);
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
                return $this->redirect(
                    $this->generateUrl(
                        'oktothek_show_series_blogpost',
                        [
                            'uniqID' => $notification->getPost()->getSeries()->getUniqID(),
                            'slug' => $notification->getPost()->getSlug()
                        ]
                    )
                );
                break;
            case Notification::NEW_EPISODE:
                return $this->redirect(
                    $this->generateUrl(
                        'oktothek_show_episode',
                        [
                            'uniqID' => $notification->getEpisode()->getUniqID()
                        ]
                    )
                );
                break;
            case Notification::LIVESTREAM:
                return $this->redirect($this->generateUrl('tv'));
                break;
        }
    }

    /**
     * @Route("/show/{username}", name="oktothek_show_user")
     * @Template()
     */
    public function showAction(User $user)
    {
        return ['user' => $user];
    }
}
