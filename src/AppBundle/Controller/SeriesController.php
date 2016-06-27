<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use MediaBundle\Entity\Series;
use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
/**
 * Series controller.
 *
 * @Route("/oktothek")
 */
class SeriesController extends Controller
{
    /**
     * @Route("/series/{uniqID}/blog", name="oktothek_series_blog_post")
     * @Method({"GET", "POST"})
     * @Template
     */
    public function blogSeriesAction(Request $request, Series $series)
    {
        $post = new Post();
        $form = $this->createForm(new PostType(), $post, ['action' => $this->generateUrl('oktothek_series_blog_post', ['uniqID' => $series->getUniqID()])]);
        $form->add('submit', 'submit', ['label' => 'oktothek.post_create_button', 'attr' => ['class' => 'btn btn-primary']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $series->addPost($post);
                $em->persist($post);
                $em->persist($series);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'oktothek.success_create_post');

                return $this->redirect($this->generateUrl('oktothek_show_series', ['uniqID' => $series->getUniqID()]));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_create_post');
            }
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/series/{uniqID}/episodes_with_tags_ajax", name="oktothek_series_episodes_with_tags_ajax")
     * @Method({"POST", "GET"})
     * @Template()
     */
    public function episodesWithTagsAjaxAction(Request $request, Series $series)
    {
        $em = $this->getDoctrine()->getManager();
        $episodes = [];
        if ($request->getMethod() == "POST") {
            if ($request->request->get('tag') == "all") {
                $episodes = $em->getRepository('MediaBundle:Series')->findNewestEpisodesForSeries($series);
                return $this->render('AppBundle::Default/EpisodeStack.html.twig', ['episodes' => $episodes]);
            } else {
                $tag = $em->getRepository('AppBundle:Tag')->findOneBy(['slug' => $request->request->get('tag')]);
                $episodes = $em->getRepository('MediaBundle:Series')->findEpisodesWithTag($series, $tag);
                return $this->render('AppBundle::Default/EpisodeStack.html.twig', ['episodes' => $episodes]);
            }
        } else {
            $episodes = $em->getRepository('MediaBundle:Series')->findNewestEpisodesForSeries($series);
        }
        $tags = $em->getRepository('MediaBundle:Series')->getSeriesTags($series);
        return ['episodes' => $episodes, 'series' => $series, 'series_tags' => $tags];
    }

    /**
     * @Route("/my_channels", name="oktothek_my_channels")
     * @Method({"GET"})
     * @Template()
     * @Security("has_role('ROLE_OKTOLAB_PRODUCER')")
     */
    public function userChannelsAction()
    {
        $series = $this->getUser()->getChannels();
        return ['series' => $series];
    }

    /**
     * @Route("/channel/{uniqID}", name="oktothek_channel")
     * @Method({"GET"})
     * @Template()
     */
    public function producerAction(Series $series)
    {
        $this->denyAccessUnlessGranted('view_channel', $series);
        return ['series' => $series];
    }
}
