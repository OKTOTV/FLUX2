<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Playlist;
use AppBundle\Entity\PlaylistItem;
use AppBundle\Form\PlaylistType;
use AppBundle\Form\PlaylistUserType;

/**
 * Playlist controller.
 *
 * @Route("/playlist")
 */
class PlaylistController extends Controller
{
    /**
     * @Route("/ajax/new", name="oktothek_playlist_new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $user = $this->get('security.context')->getToken()->getUser();
            $uniqID = $request->request->get('uniqID');
            $name = $request->request->get('name');
            $this->get('oktothek_playlist_service')->newPlaylist($name, $user, $uniqID);
            return new Response();
        }
        return $this->redirect($this->generateUrl('oktothek_playlist_index'));
    }

    /**
     * @Route("/show/{uniqID}/{page}.{_format}", name="oktothek_show_playlist", requirements={"page": "\d+"}, defaults={"page": 1, "_format": "html"})
     * @ ParamConverter("playlist", class="MediaBundle:Playlist", options={"mapping": {"playlist": "uniqID"}})
     * @Template()
     */
    public function showAction(Playlist $playlist, $page)
    {
        return ['playlist' => $playlist];

        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT i FROM MediaBundle:Playlistitems i JOIN i.episode WHERE i.playlist = :playlist";
        $query = $em->createQuery($dql);
        $query->setParameter('playlist', $playlist->getId());
        $paginator = $this->get('knp_paginator');
        $items = $paginator->paginate($query, $page, 10);
        return ['playlist' => $playlist, 'items' => $items];
    }

    /**
     * @Route("/edit/{uniqID}", name="oktothek_edit_playlist")
     * @Template()
     */
    public function editAction(Request $request, Playlist $playlist)
    {
        //TODO: voter
        $form = $this->createForm(new PlaylistUserType(), $playlist);
        $form->add('delete', 'submit', ['label' => 'oktothek.playlist_delete_button', 'attr' => ['class' => 'btn btn-danger']]);
        $form->add('submit', 'submit', ['label' => 'oktothek.playlist_update_button', 'attr' => ['class' => 'btn btn-primary']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                if ($form->get('submit')->isClicked()) { // update post
                    $em->persist($playlist);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_edit_playlist');
                    return $this->redirect($this->generateUrl('oktothek_show_playlist', ['uniqID'=> $playlist->getUniqID()]));
                } else { // delete post
                    $em->remove($playlist);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_delete_playlist');
                    return $this->redirect($this->generateUrl('user_playlists'));
                }
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_edit_playlist');
            }
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{page}", name="oktothek_playlist_index", requirements={"page": "\d+"}, defaults={"page": 1})
     * @Template()
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT p FROM MediaBundle:Playlist p";
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $playlists = $paginator->paginate($query, $page, 10);

        return ['pagination' => $playlists];
    }

    /**
     * @Route("/ajax", name="oktothek_playlist_ajax")
     * @Method("POST")
     */
    public function ajaxPlaylistAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $playlistID = $request->request->get('uniqID');
            $episodeID = $request->request->get('episodeID');
            $action = $request->request->get('action');
            $user = $this->get('security.context')->getToken()->getUser();
            if ($action == "add") {
                $this->get('oktothek_playlist_service')->addToPlaylist($episodeID, $playlistID, $user);
            } else {
            $this->get('oktothek_playlist_service')->removeFromPlaylist($episodeID, $playlistID, $user);
            }
            return new Response();
        }
        return $this->redirect($this->generateUrl('oktothek_show_user_playlist'));
    }
}
