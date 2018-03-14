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
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Search controller.
 *
 * @Route("/search")
 */
class SearchController extends Controller
{
    /**
     * @Route(".{_format}", name="oktothek_search", defaults={"_format": "html"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function searchAction(Request $request)
    {
        $data = ['search' => ''];
        $form = $this->createFormBuilder($data)
            ->setAction($this->generateUrl('oktothek_search'))
            ->add('search', TextType::class,
                [
                    'constraints' => [new NotBlank(['message' => 'oktothek.search_empty_notice'])],
                    'attr' => ['placeholder' => 'oktothek.searchfield_placeholder']
                ]
            )
            ->add('search_submit', SubmitType::class, ['label' => "oktothek.searchfiled_submit"])
            ->getForm();

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();
                $searchphrase = str_replace('/', '', $data['search']);
                $search = $this->get('oktothek_search');
                $episodes = $search->searchEpisodes($searchphrase);
                $series = $search->searchSeries($searchphrase);
                $playlists = $search->searchPlaylists($searchphrase);
                $coursetypes = $search->searchCourseTypes($searchphrase);

                return $this->render('AppBundle::search/results.html.twig', [
                    'episodes' => $episodes,
                    'seriess' => $series,
                    'playlists' => $playlists,
                    'coursetypes' => $coursetypes,
                    'searchphrase' => $data['search']
                ]);
            } else {
                return $this->redirect($this->generateUrl('homepage'));
            }
        }
        return ['searchform' => $form->createView()];
    }

    /**
     * @Route("/all/episodes.{_format}", name="oktothek_detailed_episode_search", defaults={"_format": "html"})
     * @Method({"GET"})
     * @Template()
     */
    public function searchResultsForEpisodesAction(Request $request)
    {
        $results = $this->get('oktothek_search')->searchEpisodes($request->query->get('phrase'), true, 120);
        $paginator = $this->get('knp_paginator');

        $episodes = $paginator->paginate($results, $request->query->get('page', 1), $request->query->get('limit', 12));
        return ['episodes' => $episodes, 'phrase' => $request->query->get('phrase')];
    }

    /**
     * @Route("/all/series.{_format}", name="oktothek_detailed_series_search", defaults={"_format": "html"})
     * @Method({"GET"})
     * @Template()
     */
    public function searchResultsForSeriesAction(Request $request)
    {
        $results = $this->get('oktothek_search')->searchSeries($request->query->get('phrase'), true, 120);
        $paginator = $this->get('knp_paginator');

        $seriess = $paginator->paginate($results, $request->query->get('page', 1), $request->query->get('limit', 12));
        return ['seriess' => $seriess, 'phrase' => $request->query->get('phrase')];
    }

    /**
     * @Route("/episodes/{query}", name="oktothek_search_episodes")
     * @Method({"GET"})
     */
    public function searchEpisodeAction($query)
    {
        $episodes = $this->get('oktothek_search')->searchEpisodes($query);
        $assetHelper = $this->get('bprs.asset_helper');

        $data = [];
        foreach($episodes as $episode) {
            $data[] = [
                'uniqID' => $episode->getUniqID(),
                'name' => $episode->getName(),
                'series' => $episode->getSeries()->getName(),
                'thumb' => $assetHelper->getThumbnail($episode->getPosterframe(true), 135, 240),
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
        $tags = $this->get('oktothek_search')->searchTags($query, 10);
        $data = [];
        foreach ($tags as $tag) {
            $data[] = ['name' => $tag->getText(), 'slug' => $tag->getSlug(), 'rank' => $tag->getRank()];
        }

        return new JsonResponse($data);
    }
}
