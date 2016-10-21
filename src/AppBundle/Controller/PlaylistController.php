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
use AppBundle\Form\PlaylistType;
use AppBundle\Form\PlaylistUserType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
        $form = $this->createForm(PlaylistUserType::class, $playlist);
        $form->add('submit', SubmitType::class, ['label' => 'oktothek.playlist_update_button', 'attr' => ['class' => 'btn btn-primary']]);
        $form->add('delete', SubmitType::class, ['label' => 'oktothek.playlist_delete_button', 'attr' => ['class' => 'btn btn-danger']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                if ($form->get('submit')->isClicked()) { // update post
                    $em->persist($playlist);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_edit_playlist');
                    return $this->redirect($this->generateUrl('oktothek_show_playlist', ['uniqID'=> $playlist->getUniqID()]));
                } else { // delete playlist
                    $em->remove($playlist);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_delete_playlist');
                    return $this->redirect($this->generateUrl('oktothek_user_playlists', ['username' => $this->getUser()->getUsername()]));
                }
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_edit_playlist');
            }
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/", name="oktothek_playlist_index")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $page = $request->query->get('page', 1);
        $results = $request->query->get('results', 10);
        $results = ($results > 20) ? 20 : $results;
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AppBundle:Playlist')->findNewestPlaylists(0, true);
        $paginator = $this->get('knp_paginator');
        $playlists = $paginator->paginate($query, $page, $results);

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
