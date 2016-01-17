<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Playlist;
use AppBundle\Form\PlaylistType;

/**
 * Playlist controller.
 *
 * @Route("/playlist")
 */
class PlaylistController extends Controller
{
    /**
     * @Route("/new", name="oktothek_playlist_new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $playlist = new Playlist();
        $form = $this->createForm(new PlaylistType(), $playlist);
        $form->add('submit', 'submit', array('label' => 'oktolab.playlist_create_button'));

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $playlist->setUser($this->get('security.context')->getToken()->getUser());
                $em = $this->getDoctrine()->getManager();
                $em->persist($playlist);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'playlist_create.success');
                return $this->redirect($this->generateUrl('oktothek_show_playlist', array('uniqID' => $playlist->getUniqID())));
            }
            $this->get('session')->getFlashBag()->add('error', 'playlist_create.error');
        }
        return array('form' => $form->createView());
    }

    /**
     * @Route("{playlist}/edit", name="playlist_edit")
     * @Template()
     */
    public function editAction(Playlist $playlist)
    {
        $form = $this->createForm(new PlaylistType(), $playlist);
        $form->add('submit', 'submit', array('label' => 'oktolab.playlist_create_button'));

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                if ($form->get('submit')->isClicked()) {
                    $em->persist($playlist);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'playlist_create.success');
                    return $this->redirect($this->generateUrl('oktothek_show_playlist', array('uniqID' => $playlist->getUniqID())));
                } elseif ($form->get('delete')->isClicked()) {
                    $em->remove($episode);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_delete_episode');
                    return $this->redirect($this->generateUrl('oktothek_playlist_index'));
                } else { //???
                    $this->get('session')->getFlashBag()->add('info', 'oktothek.info_edit_playlist_unknown_button');
                    return $this->redirect($this->generateUrl('oktolab_playlist_show', ['uniqID' => $playlist->getUniqID()]));
                }
            }
            $this->get('session')->getFlashBag()->add('error', 'oktothek.playlist_create.error');
        }
        return array('form' => $form->createView());
    }

    /**
     * @Route("/show/{playlist}/{page}", name="oktothek_playlist_show", requirements={"page": "\d+"}, defaults={"page": 1})
     * @Template()
     */
    public function showAction(Playlist $playlist, $page)
    {
        return ['playlist' => $playlist];
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
