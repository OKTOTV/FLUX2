<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Episode;
use AppBundle\Entity\Series;
use AppBundle\Entity\Playlist;
use AppBundle\Entity\Post;
use AppBundle\Entity\News;
use AppBundle\Entity\Tag;
use AppBundle\Form\TagType;

/**
 * Tag controller.
 *
 * @Route("/tag")
 */
class TagController extends Controller
{
    /**
     * @Route("/menu")
     * @Template()
     */
    public function menuTagsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tags = $em->getRepository('AppBundle:Tag')->findAll();
        return ['tags' => $tags];
    }

    /**
     * @Route("/new", name="oktothek_tag_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $tag = new Tag();
        $form = $this->createForm(new TagType(), $tag);
        $form->add('submit', 'submit', ['label' => 'oktothek.tag_create_button', 'attr' => ['class' => 'btn btn-primary']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($tag);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'oktothek.success_create_tag');

                return $this->redirect($this->generateUrl('oktothek_tag_index'));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_create_tag');
            }
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/ajax", name="oktothek_tag_ajax")
     * @Method({"GET", "POST"})
     */
    public function ajaxTagAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            if ($request->getMethod() =="GET") {
                $tags = $em->getRepository('AppBundle:Tag')->findAll();
                $json = [];
                foreach ($tags as $tag) {
                    $json[] = $tag->getText();
                }
                return new Response(json_encode($json));
            } else { //Posts tag
                $action = $request->request->get('action');
                if ($action = "add") { // add new tag
                    $tag = $em->getRepository('AppBundle:Tag')->findOneBy(['text' => $request->request->get('text')]);
                    if (!$tag) {
                        $tag = new Tag();
                        $tag->setText($request->request->get('text'));
                        $em->persist($tag);
                        $em->flush();
                    }
                    return new Response();
                } else { // delete tag
                    $tag = $em->getRepository('AppBundle:Tag')->findOneBy(['text' => $request->request->get('text')]);
                    $em->remove($tag);
                    $em->flush();

                    return new Response();
                }
            }
        }
        return $this->redirect('oktothek_tag_index');
    }

    /**
     * @Route("/", name="oktothek_tag_index")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tags = $em->getRepository('AppBundle:Tag')->findAll();
        return ['tags' => $tags];
    }

    /**
     * @Route("/{slug}", name="oktothek_tag_show")
     * @ParamConverter("slug", class="AppBundle:Tag", options={"mapping": {"slug": "slug"}})
     * @Template()
     */
    public function tagAction(Tag $slug)
    {
        return ['tag' => $slug];
    }

    /**
     * @Route("/{tag}/episodes")
     * @ParamConverter("tag", class="AppBundle:Tag", options={"mapping": {"tag": "slug"}})
     * @Template()
     */
    public function episodeTagAction(Tag $tag)
    {
        $em = $this->getDoctrine()->getManager();
        $episodes = $em->getRepository('AppBundle:Tag')->findEpisodesWithTag($tag);

        return ['episodes' => $episodes];
    }

    /**
     * @Route("/{tag}/series")
     * @ParamConverter("tag", class="AppBundle:Tag", options={"mapping": {"tag": "slug"}})
     * @Template()
     */
    public function seriesTagAction(Tag $tag)
    {
        $em = $this->getDoctrine()->getManager();
        $seriess = $em->getRepository('AppBundle:Tag')->findSeriesWithTag($tag);

        return ['seriess' => $seriess];
    }

    /**
     * @Route("/{tag}/posts")
     * @ParamConverter("tag", class="AppBundle:Tag", options={"mapping": {"tag": "slug"}})
     * @Template()
     */
    public function postsTagAction(Tag $tag)
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('AppBundle:Tag')->findPostsWithTag($tag);

        return ['posts' => $posts];
    }

    /**
     * @Route("/{tag}/pages")
     * @ParamConverter("tag", class="AppBundle:Tag", options={"mapping": {"tag": "slug"}})
     * @Template()
     */
    public function pagesTagAction(Tag $tag)
    {
        $em = $this->getDoctrine()->getManager();
        $pages = $em->getRepository('AppBundle:Tag')->findPagesWithTag($tag);

        return ['pages' => $pages];
    }

    /**
     * @Route("/{tag}/playlists")
     * @ParamConverter("tag", class="AppBundle:Tag", options={"mapping": {"tag": "slug"}})
     * @Template()
     */
    public function playlistTagAction(Tag $tag)
    {
        $em = $this->getDoctrine()->getManager();
        $playlists = $em->getRepository('AppBundle:Tag')->findPlaylistWithTag($tag);

        return ['playlists' => $playlists];
    }
}
