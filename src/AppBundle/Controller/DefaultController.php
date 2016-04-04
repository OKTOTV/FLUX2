<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\RegisterType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function oktothekAction()
    {
        $em = $this->getDoctrine()->getManager();
        $best_episodes = $em->getRepository('AppBundle:Episode')->findTopEpisodes(4);
        $newest_episodes = $em->getRepository('AppBundle:Episode')->findNewestEpisodes(4);
        $newest_playlists = $em->getRepository('AppBundle:Playlist')->findNewestPlaylists(4);

        return array(
            'best_episodes' => $best_episodes,
            'newest_episodes' => $newest_episodes,
            'newest_playlists' => $newest_playlists
        );
    }

    /**
     * @Route("/tv", name="tv")
     * @Template
     */
    public function tvAction()
    {
        return array();
    }

    /**
     * @Route("/participate", name="participate")
     * @Template
     */
    public function participateAction()
    {
        return array();
    }

    /**
    * @Route("/slider", name="slider")
    * @Template()
    */
    public function sliderAction()
    {
        $em = $this->getDoctrine()->getManager();
        $episodePins = $em->getRepository('AppBundle:EpisodePin')->findBy(array(), array('onlineAt' => 'ASC'), 5, 0);

        return array('slides' => $episodePins);
    }

    /**
     * @Route("/register", name="register")
     * @Template()
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(new RegisterType(), $user);
        $form->add('submit', 'submit', ['label' => 'oktothek.register_user_button', 'attr' => ['class' => 'btn btn-default']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->get('bprs_user.user')->createUser($user, User::ROLE_USER);
                $this->get('session')->getFlashBag()->add('success', 'oktothek.success_create_account');

                return $this->redirect($this->generateUrl('homepage'));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_create_account');
            }
        }

        return ['form' => $form->createView()];
    }
}
