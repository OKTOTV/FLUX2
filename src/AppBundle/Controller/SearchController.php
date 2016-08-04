<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
            ->add('submit', SubmitType::class, ['label' => "oktothek.searchfiled_submit"])
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

    /**
     * @Route("/episodes/{query}", name="oktothek_search_episodes")
     * @Method({"GET"})
     */
    public function searchEpisodeAction($query)
    {
        $episodes = $this->get('oktothek_search')->searchEpisodes($query);
        $assetHelper = $this->get('bprs.asset_helper');
        // die(print_r($episodes));
        $data = [];
        foreach($episodes as $episode) {
            $data[] = [
                'uniqID' => $episode->getUniqID(),
                'name' => $episode->getName(),
                'thumb' => $assetHelper->getThumbnail($episode->getPosterframe(), 135, 240),
                'desc' => $episode->getDescription()
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/tags/{query}", name="oktothek_search_tags")
     * @Method({"GET"})
     */
    public function searchTagAction($query)
    {
        $tags = $this->get('oktothek_search')->searchTags($query);
        $data = [];
        foreach ($tags as $tag) {
            $data[] = ['name' => $tag->getText(), 'slug' => $tag->getSlug(), 'rank' => $tag->getRank()];
        }

        return new JsonResponse($data);
    }
}
