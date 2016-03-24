<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Course\Coursepackage;
use AppBundle\Form\Course\CoursepackageType;

/**
 * @Route("/backend/coursepackage")
 */
class CoursepackageController extends Controller
{
    /**
     * @Route("/", name="oktothek_backend_coursepackages")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $coursepackages = $em->getRepository('AppBundle:Course\Coursepackage')->findAll();

        return ['coursepackages' => $coursepackages];
    }

    /**
     * @Route("/new", name="oktothek_backend_new_coursepackage")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $coursepackage = new Coursepackage();
        $em = $this->getDoctrine()->getManager();
        $coursetypes = $em->getRepository('AppBundle:Course\Coursetype')->findAll();

        $form = $this->createForm(new CoursepackageType(), $coursepackage, ['coursetypes' => $coursetypes]);
        $form->add('submit', 'submit', ['label' => 'oktothek.coursepackage_create_button', 'attr' => ['class' => 'btn btn-primary']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($coursepackage);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'oktothek.success_create_coursepackage');

                return $this->redirect($this->generateUrl('oktothek_backend_coursepackages'));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_create_coursepackage');
            }
        }

        return ['form' => $form->createView()];
    }
}
