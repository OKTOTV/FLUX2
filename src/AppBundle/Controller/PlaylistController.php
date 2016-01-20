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
     * @Route("/show/{playlist}/{page}", name="oktothek_playlist_show", requirements={"page": "\d+"}, defaults={"page": 1})
     * @ParamConverter("playlist", class="AppBundle:Playlist", options={"mapping": {"playlist": "uniqID"}})
     * @Template()
     */
    public function showAction(Playlist $playlist, $page)
    {
        return ['playlist' => $playlist];

        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT i FROM AppBundle:Playlistitems i JOIN i.episode WHERE i.playlist = :playlist";
        $query = $em->createQuery($dql);
        $query->setParameter('playlist', $playlist->getId());
        $paginator = $this->get('knp_paginator');
        $items = $paginator->paginate($query, $page, 10);
        return ['playlist' => $playlist, 'items' => $items];
    }

    /**
     * @Route("/{page}", name="oktothek_playlist_index", requirements={"page": "\d+"}, defaults={"page": 1})
     * @Template()
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT p FROM AppBundle:Playlist p";
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
