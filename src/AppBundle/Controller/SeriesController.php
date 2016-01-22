<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Series;
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
     * @Route("/series/{series}/blog", name="oktothek_series_blog_post")
     * @Method({"GET", "POST"})
     * @Template
     */
    public function blogSeriesAction(Request $request, Series $series)
    {
        $post = new Post();
        $form = $this->createForm(new PostType(), $post, ['action' => $this->generateUrl('oktothek_series_blog_post', ['series' => $series->getUniqID()])]);
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

                return $this->redirect($this->generateUrl('oktothek_series_show', ['uniqID' => $series->getUniqID()]));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_create_post');
            }
        }

        return ['form' => $form->createView()];
    }
}
