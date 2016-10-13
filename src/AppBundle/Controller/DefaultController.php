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
        $best_episodes = $em->getRepository('AppBundle:Episode')->findTopEpisodes(8);
        $newest_episodes = $em->getRepository('AppBundle:Episode')->findNewestEpisodes(8);
        $newest_playlists = $em->getRepository('AppBundle:Playlist')->findNewestPlaylists(8);
        return [
            'best_episodes'    => $best_episodes,
            'newest_episodes'  => $newest_episodes,
            'newest_playlists' => $newest_playlists
        ];
    }

    /**
     * @Route("/participate", name="participate")
     * @Template
     */
    public function participateAction()
    {
        return [];
    }

    /**
    * @Route("/slider/{number}.{_format}", name="slider", requirements={"number": "\d+", "_format": "html|json"}, defaults={"number": 5, "_format": "html"})
    * @Template()
    */
    public function sliderAction($number = 5)
    {
        $em = $this->getDoctrine()->getManager();
        $slides = $em->getRepository('AppBundle:Slide')->findNewestSlides($number);

        return ['slides' => $slides];
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
