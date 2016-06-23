<?php

namespace MediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Series;
use MediaBundle\Entity\Reachme;
use MediaBundle\Form\ReachmeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Reachme controller.
 *
 * @Route("/oktolab_media/reachme")
 */
class ReachmeController extends Controller
{
    /**
     * @Route("/{reachme}/show", name="flux2_reachme_show")
     * @Method({"GET"})
     * @Template()
     */
    public function showAction(Reachme $reachme)
    {
        return ['reachme' => $reachme];
    }

    /**
     * @Route("/new/{series_uid}", name="flux2_reachme_new", defaults={"series_uid" = "0"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request, $series_uid)
    {
        $reachme = new Reachme();
        if ($series_uid) {
            $series = $this->get('oktolab_media')->getSeries($series_uid);
            $reachme->setSeries($series);
        }
        $form = $this->createForm(new ReachmeType($this->get('translator')), $reachme);
        $form->add('submit', SubmitType::class, ['label' => 'flux2_media.create_reachme_button', 'attr' => ['class' => 'btn btn-primary']]);

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            if ($form->isValid()) { //form is valid, save or preview
                $em->persist($reachme);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'flux2_media.success_create_reachme');
                return $this->redirect($this->generateUrl('flux2_reachme_show', ['reachme' => $reachme->getID()]));
            }
            $this->get('session')->getFlashBag()->add('error', 'flux2_media.error_create_reachme');
        }
        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{reachme}/edit", name="flux2_reachme_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Request $request, Reachme $reachme)
    {
        $form = $this->createForm(new ReachmeType($this->get('translator')), $reachme);
        $form->add('submit', SubmitType::class, ['label' => 'flux2_media.edit_reachme_button', 'attr' => ['class' => 'btn btn-primary']]);
        $form->add('delete', SubmitType::class, ['label' => 'flux2_media.delete_reachme_button', 'attr' => ['class' => 'btn btn-danger']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            if ($form->isValid()) { //form is valid, save or preview
                if ($form->get('submit')->isClicked()) { //save me
                    $em->persist($reachme);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'flux2_media.success_edit_reachme');
                    return $this->redirect($this->generateUrl('flux2_reachme_show', ['reachme' => $reachme->getID()]));
                } elseif ($form->get('delete')->isClicked()) {
                    $id = $reachme->getSeries()->getId();
                    $em->remove($reachme);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'flux2_media.success_delete_reachme');
                    return $this->redirect($this->generateUrl('oktolab_series_show', ['series' => $id]));
                } else { //???
                    $this->get('session')->getFlashBag()->add('success', 'flux2_media.info_edit_reachme_unknown_button');
                    return $this->redirect($this->generateUrl('flux2_reachme_show', ['reachme' => $reachme->getID()]));
                }
            }
            $this->get('session')->getFlashBag()->add('error', 'flux2_media.error_edit_reachme');
        }

        return ['form' => $form->createView()];
    }
}
