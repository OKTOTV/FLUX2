<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Search controller.
 *
 * @Route("/search")
 */
class SearchController extends Controller
{
    /**
     * @Route("/", name="oktothek_search")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function searchAction(Request $request)
    {
        $data = ['search' => ''];
        $form = $this->createFormBuilder($data)
            ->setAction($this->generateUrl('oktothek_search'))
            ->add('search', TextType::class, ['attr' => ['placeholder' => 'oktothek.searchfield_placeholder']])
            ->add('submit', SubmitType::class, ['label' => " "])
            ->getForm();

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();
                $search = $this->get('oktothek_search');
                $episodes = $search->searchEpisodes($data['search']);
                $series = $search->searchSeries($data['search']);
                $playlists = $search->searchPlaylists($data['search']);

                return $this->render('AppBundle::Search/results.html.twig', [
                    'episodes' => $episodes,
                    'series' => $series,
                    'playlists' => $playlists,
                    'searchphrase' => $data['search']
                ]);
            }
        }
        return ['searchform' => $form->createView()];
    }
}
