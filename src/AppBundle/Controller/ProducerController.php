<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Series;
use AppBundle\Entity\Episode;
use AppBundle\Entity\Post;
use AppBundle\Entity\Playlist;
use AppBundle\Form\Series\PostType;
use AppBundle\Form\PlaylistUserType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Producer controller.
 * Sites for producers and assoc. ppl. Analytics, blogging, playlists and more
 *
 * @Route("/producer")
 * @Security("has_role('ROLE_OKTOLAB_PRODUCER')")
 */
class ProducerController extends Controller
{
    /**
     * @Route("/", name="oktothek_my_channels")
     * @Method({"GET"})
     * @Template()
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

    /**
     * @Route("/channel/{uniqID}/blog", name="oktothek_series_blog_post")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function blogAction(Request $request, Series $series)
    {
        $this->denyAccessUnlessGranted('edit_channel', $series);
        $post = new Post();
        $post->setIsActive(true);
        $form = $this->createForm(PostType::class, $post, ['action' => $this->generateUrl('oktothek_series_blog_post', ['uniqID' => $series->getUniqID()])]);
        $form->add('submit', SubmitType::class, ['label' => 'oktothek.post_create_button', 'attr' => ['class' => 'btn btn-primary']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $series->addPost($post);
                foreach ($post->getAssets() as $asset) {
                    $asset->setSeries($series);
                    $em->persist($asset);
                }
                $em->persist($post);
                $em->persist($series);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'oktothek.success_create_post');
                $this->get('oktothek_notification_service')->createNewPostNotifications($post);
                return $this->redirect($this->generateUrl('oktothek_channel_blogposts', ['uniqID' => $series->getUniqID()]));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_create_post');
            }
        }

        return ['form' => $form->createView(), 'series' => $series];
    }

    /**
     * @Route("/channel/{uniqID}/edit/{post}", name="oktothek_series_edit_blog_post")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editBlogAction(Request $request, Series $series, Post $post)
    {
        $this->denyAccessUnlessGranted('edit_channel', $series);
        $form = $this->createForm(PostType::class, $post);
        $form->add('delete', SubmitType::class, ['label' => 'oktothek.post_delete_button', 'attr' => ['class' => 'btn btn-danger']]);
        $form->add('submit', SubmitType::class, ['label' => 'oktothek.post_update_button', 'attr' => ['class' => 'btn btn-primary']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                if ($form->get('submit')->isClicked()) { // update
                    foreach ($post->getAssets() as $asset) {
                        $asset->setSeries($series);
                        $em->persist($asset);
                    }
                    $em->persist($post);
                    $em->persist($series);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_update_post');

                    return $this->redirect($this->generateUrl('oktothek_channel_blogposts', ['uniqID' => $series->getUniqID()]));
                } elseif ($form->get('delete')->isClicked()) { // delete
                    $this->get('oktothek_post_service')->deletePost($post);
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_delete_post');
                    return $this->redirect($this->generateUrl('oktothek_channel_blogposts', ['uniqID' => $series->getUniqID()]));
                } else {
                    $this->get('session')->getFlashBag()->add('info', 'oktothek.success_unknown_post');
                    return $this->redirect($this->generateUrl('oktothek_channel_blogposts', ['uniqID' => $series->getUniqID()]));
                }
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_update_post');
            }
        }

        return ['form' => $form->createView(), 'series' => $series];
    }

    /**
     * @Route("/channel/{uniqID}/episodes/{page}", name="oktothek_channel_episodes", defaults={"page": 1}, requirements={"page": "\d+"})
     * @Method({"GET"})
     * @Template()
     */
    public function episodesAction(Series $series, $page)
    {
        $this->denyAccessUnlessGranted('view_channel', $series);
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $episodes = $paginator->paginate($em->getRepository('AppBundle:Episode')->findEpisodesForSeries($series, true), $page, 3);
        return ['episodes' => $episodes, 'series' => $series];
    }

    /**
     * @Route("/channel/episode/{uniqID}", name="oktothek_channel_episode")
     * @Method({"GET"})
     * @Template()
     */
    public function episodeAction(Episode $episode)
    {
        $this->denyAccessUnlessGranted('view_channel', $episode->getSeries());
        $analytics = $this->get('oktothek_producer_analytics');
        $clicks2weeks = $analytics->getAnalyticsForEpisode($episode);
        $clicks48 = $analytics->getAnalyticsForEpisode($episode, '+1 hour', '-2 days');
        return ['episode' => $episode, 'clicks2weeks' => $clicks2weeks, 'clicks48' => $clicks48];
    }

    public function producerNewPlaylistAction(Request $request, Series $series)
    {
        $this->denyAccessUnlessGranted('edit_channel', $series);
    }

    /**
     * @Route("/channel/{uniqID}/playlists/{page}", name="oktothek_channel_playlists", defaults={"page": 1}, requirements={"page": "\d+"})
     * @Method({"GET"})
     * @Template()
     */
    public function playlistsAction(Series $series, $page)
    {
        $this->denyAccessUnlessGranted('view_channel', $series);
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $playlists = $paginator->paginate($em->getRepository('AppBundle:Playlist')->findPlaylistsForSeries($series), $page, 8);
        return ['playlists' => $playlists, 'series' => $series];
    }

    /**
     * @Route("/channel/{uniqID}/playlist/new", name="oktothek_channel_playlist_new")
     * @Template()
     */
    public function newPlaylistAction(Request $request, Series $series)
    {
        $this->denyAccessUnlessGranted('edit_channel', $series);
        $playlist = new Playlist();
        $playlist->setUser($this->getUser());
        $playlist->setSeries($series);
        $form = $this->createForm(PlaylistUserType::class, $playlist);
        $form->add('submit', SubmitType::class, ['label' => 'oktothek.playlist_create_button', 'attr' => ['class' => 'btn btn-primary']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                if ($form->get('submit')->isClicked()) { // update post
                    $em->persist($playlist);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_edit_playlist');
                    return $this->redirect($this->generateUrl('oktothek_show_playlist', ['uniqID'=> $playlist->getUniqID()]));
                }
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_edit_playlist');
            }
        }

        return ['form' => $form->createView(), 'series' => $series];
    }
    /**
     * @Route("/channel/playlist/{uniqID}/edit", name="oktothek_channel_playlist_edit")
     * @Template()
     */
    public function editPlaylistAction(Request $request, Playlist $playlist)
    {
        $this->denyAccessUnlessGranted('edit_channel', $playlist->getSeries());
        $form = $this->createForm(PlaylistUserType::class, $playlist);
        $form->add('submit', SubmitType::class, ['label' => 'oktothek.playlist_update_button', 'attr' => ['class' => 'btn btn-primary']]);
        $form->add('delete', SubmitType::class, ['label' => 'oktothek.playlist_delete_button', 'attr' => ['class' => 'btn btn-link']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                if ($form->get('submit')->isClicked()) { // update post
                    $em->persist($playlist);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_edit_playlist');
                    return $this->redirect($this->generateUrl('oktothek_channel_playlists', ['uniqID'=> $playlist->getSeries()->getUniqID()]));
                } else { // delete playlist
                    $em->remove($playlist);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_delete_playlist');
                    return $this->redirect($this->generateUrl('oktothek_channel_playlists', ['uniqID' => $playlist->getSeries()->getUniqID()]));
                }
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_edit_playlist');
            }
        }

        return ['form' => $form->createView(), 'series' => $playlist->getSeries()];
    }

    /**
     * @Route("/channel/{uniqID}/blogposts/{page}", name="oktothek_channel_blogposts", defaults={"page": 1}, requirements={"page": "\d+"})
     * @Method({"GET"})
     * @Template()
     */
    public function blogpostsAction(Series $series, $page)
    {
        $this->denyAccessUnlessGranted('view_channel', $series);
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $posts = $paginator->paginate($em->getRepository('AppBundle:Post')->findPostsForSeriesQuery($series), $page, 8);
        return ['posts' => $posts, 'series' => $series];
    }

    /**
     * @Route("/channel/{uniqID}/now_live", name="oktothek_channel_now_live")
     * @Template()
     */
    public function nowLiveAction(Request $request, Series $series)
    {
        $this->denyAccessUnlessGranted('view_channel', $series);
        $defaultData = ['confirm' => false];
        $form = $this->createFormBuilder($defaultData)
            ->add('confirm', CheckboxType::class,
                [
                    'label' => 'oktothek_channel_live_confirm_label',
                    'constraints' => [new IsTrue(['message' => 'oktothek.error_checkbox_notice'])]
                ]
            )
            ->add('submit', SubmitType::class, ['label' => 'oktothek.notificate_live_button', 'attr' => ['class' => 'btn btn-primary']])
            ->getForm();

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->get('oktothek_notification_service')->createLivestreamNotifications($series);
                $this->get('session')->getFlashBag()->add('success', 'oktothek.success_notificate_livestream');
                return $this->redirect($this->generateUrl('oktothek_channel', ['uniqID' => $series->getUniqID()]));
            }
            $this->get('session')->getFlashBag()->add('error', 'oktothek.error_notificate_livestream');
        }
        return ['form' => $form->createView(), 'series' => $series];
    }
}
