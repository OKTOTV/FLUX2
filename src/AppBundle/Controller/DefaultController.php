<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\RegisterType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function oktothekAction()
    {
        $em = $this->getDoctrine()->getManager();
        $episode_repo = $em->getRepository('AppBundle:Episode');
        $best_episodes = $episode_repo->findTrendingEpisodes();//findBestEpisodes();
        $newest_episodes = $episode_repo->findNewestEpisodes();
        $newest_playlists = $em->getRepository('AppBundle:Playlist')->findNewestPlaylists();
        return [
            'best_episodes'    => $best_episodes,
            'newest_episodes'  => $newest_episodes,
            'newest_playlists' => $newest_playlists
        ];
    }

    /**
     * @Route("/watch", name="shorturl")
     * @Method("GET")
     */
    public function episodeShortAction(Request $request)
    {
        if ($request->query->get('v')) {
            return $this->redirect($this->generateUrl(
                'oktothek_show_episode',
                ['uniqID' => $request->query->get('v')]
            ));
        } elseif ($request->query->get('p')) {
            return $this->redirect($this->generateUrl(
                'oktothek_show_playlist',
                ['uniqID' => $request->query->get('p')]
            ));
        }
        return $this->redirect('homepage');
    }

    /**
     * @Route("/participate", name="participate")
     * @Template()
     */
    public function participateAction()
    {
        return [];
    }

    /**
    * @Route(
    *    "/slider/{number}.{_format}",
    *    name="slider",
    *    requirements={"number": "\d+", "_format": "html|json"},
    *    defaults={"number": 5, "_format": "html"}
    * )
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
        $form->add('submit', SubmitType::class, [
            'label' => 'oktothek.register_user_button',
            'attr' => ['class' => 'btn btn-default']
        ]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $roles = $this->getParameter('bprs_user.user_defaults');
                $this->get('bprs_user.user')->createUser($user, $roles);
                $this->get('session')->getFlashBag()
                    ->add('success', 'oktothek.success_create_account');

                return $this->redirect($this->generateUrl('homepage'));
            } else {
                $this->get('session')->getFlashBag()
                    ->add('error', 'oktothek.error_create_account');
            }
        }

        return ['form' => $form->createView()];
    }

    public function seriesRedirectAction($webtitle)
    {
        $repo = $this->get('oktolab_media')->getSeriesRepo();
        $series = $repo->findOneBy(['webtitle' => $webtitle]);

        if ($series) {
            return $this->redirect($this->generateUrl(
                'oktothek_show_series', ['uniqID' => $series->getUniqID()]
            ));
        } else {
            $this->get('session')->getFlashBag()->add(
                'info',
                'oktothek.info_webtitle_not_found'
            );
            return $this->redirect($this->generateUrl('homepage'));
        }
    }
}
