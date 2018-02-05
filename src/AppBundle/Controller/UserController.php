<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Abonnement;
use AppBundle\Form\AbonnementType;
use AppBundle\Form\OktoAbonnementType;
use AppBundle\Entity\Notification;
use AppBundle\Entity\User;

/**
 * @Route("/user")
 * @Security("has_role('ROLE_OKTOLAB_USER')")
 */
class UserController extends Controller
{
    /**
     * @Route("/{username}/playlists", name="oktothek_user_playlists")
     * @Template()
     */
     public function playlistsAction(Request $request, User $user)
     {
         $page = $request->query->get('page', 1);
         $results = $request->query->get('results', 10);
         $results = ($results > 20) ? 20 : $results;
         $em = $this->getDoctrine()->getManager();
         $query = $em->getRepository('AppBundle:User')->findPlaylists($user, 0, true);
         $paginator = $this->get('knp_paginator');
         $playlists = $paginator->paginate($query, $page, $results);

         return ['playlists' => $playlists, 'user' => $user];
     }

     /**
      * @Route("/{username}/favorites", name="oktothek_user_favorites")
      * @Template()
      */
     public function favoritesAction(Request $request, User $user)
     {
         $page = $request->query->get('page', 1);
         $results = $request->query->get('results', 10);
         $results = ($results > 20) ? 20 : $results;
         $em = $this->getDoctrine()->getManager();
         $query = $em->getRepository('AppBundle:User')->findFavorites($user, 0, true);
         $paginator = $this->get('knp_paginator');
         $favorites = $paginator->paginate($query, $page, $results);

         return ['favorites' => $favorites, 'user' => $user];
     }

    /**
     * @Route("/{username}/channels", name="oktothek_user_channels")
     * @Template()
     */
    public function channelsAction(Request $request, User $user)
    {
        $page = $request->query->get('page', 1);
        $results = $request->query->get('results', 10);
        $results = ($results > 20) ? 20 : $results;
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $query = $em->getRepository('AppBundle:User')->findAbonnements($user, 0, true);
        $channels = $paginator->paginate($query, $page, $results);

        return ['channels' => $channels, 'user' => $user];
    }

    /**
     * @Route("/abonnements", name="oktothek_user_abonnements")
     * @Template()
     */
    public function abonnementsAction(Request $request)
    {
        $page = $request->query->get('page', 1);
        $results = $request->query->get('results', 10);
        $results = ($results > 20) ? 20 : $results;
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $query = $em->getRepository('AppBundle:User')->findAbonnements($this->getUser(), 0, true);
        $channels = $paginator->paginate($query, $page, $results);

        return ['channels' => $channels];
    }

    /**
     * @Route("/updateFavorite", name="user_update_favorite")
     */
    public function updateFavoriteAction(Request $request)
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

        $form = null;
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $form = $this->createForm(OktoAbonnementType::class, $abonnement);
        } else {
            $form = $this->createForm(AbonnementType::class, $abonnement);
        }
        $form->add('submit', SubmitType::class, ['label' => 'oktothek.user_update_abonnement_button', 'attr' => ['class' => 'btn btn-primary']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($abonnement);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'oktothek.success_edit_abonnement');

                return $this->redirect($this->generateUrl('oktothek_user_abonnements'));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_edit_post');
            }
        }

        return ['form' => $form->createView(), 'series' => $abonnement->getSeries()];
    }

    /**
     * @Route("/notification/clear_all", name="user_notification_clear_all")
     */
    public function clearAllNotificationAction()
    {
        $em = $this->getDoctrine()->getManager();
        $em->getRepository('BprsUserBundle:Notification')->setAllNotificationToReadForUser($this->getUser());
        return $this->redirect($this->generateUrl('homepage'));
    }

    /**
     * @Route("/{username}", name="oktothek_show_user")
     * @Template()
     */
    public function showAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $playlists = $em->getRepository('AppBundle:User')->findPlaylists($user);
        $favorites = $em->getRepository('AppBundle:User')->findFavorites($user);
        $channels = $em->getRepository('AppBundle:User')->findAbonnements($user);
        return [
            'user' => $user,
            'playlists' => $playlists,
            'favorites' => $favorites,
            'channels' => $channels
        ];
    }
}
