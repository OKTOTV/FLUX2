<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/create", name="playlist_create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $playlist = new Playlist();
        $form = $this->createForm(new PlaylistType(), $playlist, array(
            'action' => $this->generateUrl('playlist_create'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
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

    public function updateAction()
    {

    }

    public function deleteAction()
    {

    }
}
