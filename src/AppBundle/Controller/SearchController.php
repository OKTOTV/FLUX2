<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
// use Symfony\Component\Form\Extension\Core\Type\TextType;

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
            ->add('search', 'text'/*TextType::class*/)
            ->add('submit', 'submit', ['label' => " "])
            ->getForm();

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();
                $finder = $this->container->get('fos_elastica.finder.app.episode');
                $episodes = $finder->find($data['search']);
                // die(var_dump($episodes));
                return $this->render('AppBundle::Search/results.html.twig', ['episodes' => $episodes]);
            }
        }
        return ['searchform' => $form->createView()];
    }
}
