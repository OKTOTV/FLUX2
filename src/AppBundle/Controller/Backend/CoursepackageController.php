<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Course\Coursepackage;
use AppBundle\Entity\Course\CoursepackageSelect;
use AppBundle\Form\Course\CoursepackageType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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

        $form = $this->createForm(CoursepackageType::class, $coursepackage, ['coursetypes' => $coursetypes]);
        $form->add('submit', SubmitType::class, ['label' => 'oktothek.coursepackage_create_button', 'attr' => ['class' => 'btn btn-primary']]);

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

    /**
     * @Route("/edit/{coursepackage}", name="oktothek_backend_edit_coursepackage")
     * @Template()
     */
    public function editAction(Request $request, Coursepackage $coursepackage)
    {
        $em = $this->getDoctrine()->getManager();
        $coursetypes = $em->getRepository('AppBundle:Course\Coursetype')->findAll();

        $form = $this->createForm(CoursepackageType::class, $coursepackage, ['coursetypes' => $coursetypes]);
        $form->add('delete', SubmitType::class, ['label' => 'oktothek.coursepackage_delete_button', 'attr' => ['class' => 'btn btn-danger']]);
        $form->add('submit', SubmitType::class, ['label' => 'oktothek.coursepackage_edit_button', 'attr' => ['class' => 'btn btn-primary']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                if ($form->get('submit')->isClicked()) {
                    $em->persist($coursepackage);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_edit_coursepackage');

                    return $this->redirect($this->generateUrl('oktothek_backend_coursepackages'));
                } else { //delete
                    $em->remove($coursepackage);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_delete_coursepackage');
                    return $this->redirect($this->generateUrl('oktothek_backend_coursepackages'));
                }
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_edit_coursepackage');
            }
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/show/{coursepackage}", name="oktothek_backend_show_coursepackage")
     * @Template()
     */
    public function showAction(Coursepackage $coursepackage)
    {
        return ['coursepackage' => $coursepackage];
    }
}
