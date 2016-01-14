<?php

namespace MediaBundle\Controller;

use Oktolab\MediaBundle\Controller\EpisodeController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Episode;
use MediaBundle\Form\EpisodeType;

/**
 * Episode controller.
 *
 * @Route("/oktolab_media/episode")
 */
class EpisodeController extends BaseController
{

    /**
     * Lists all Episode entities.
     *
     * @Route("/", name="episode")
     * @Method("GET")
     * @Template()
     */
    // public function indexAction()
    // {
    //     $em = $this->getDoctrine()->getManager();
    //     $entities = $em->getRepository('AppBundle:Episode')->findAll();
    //
    //     return array(
    //         'entities' => $entities,
    //     );
    // }

    /**
     * Creates a new Episode entity.
     *
     * @Route("/", name="episode_create")
     * @Method("POST")
     * @Template("AppBundle:Episode:new.html.twig")
     */
    // public function createAction(Request $request)
    // {
    //     $entity = new Episode();
    //     $form = $this->createCreateForm($entity);
    //     $form->handleRequest($request);
    //
    //     if ($form->isValid()) {
    //         $em = $this->getDoctrine()->getManager();
    //         $em->persist($entity);
    //         $em->flush();
    //
    //         return $this->redirect($this->generateUrl('episode_show', array('id' => $entity->getId())));
    //     }
    //
    //     return array(
    //         'entity' => $entity,
    //         'form'   => $form->createView(),
    //     );
    // }

    /**
     * Creates a form to create a Episode entity.
     *
     * @param Episode $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    // private function createCreateForm(Episode $entity)
    // {
    //     $form = $this->createForm(new EpisodeType(), $entity, array(
    //         'action' => $this->generateUrl('episode_create'),
    //         'method' => 'POST',
    //     ));
    //
    //     $form->add('submit', 'submit', array('label' => 'Create'));
    //
    //     return $form;
    // }

    /**
     * Displays a form to create a new Episode entity.
     *
     * @Route("/new", name="episode_new")
     * @Method("GET")
     * @Template()
     */
    // public function newAction()
    // {
    //     $entity = new Episode();
    //     $form   = $this->createCreateForm($entity);
    //
    //     return array(
    //         'entity' => $entity,
    //         'form'   => $form->createView(),
    //     );
    // }

    /**
     * Finds and displays a Episode entity.
     *
     * @Route("/{id}", name="episode_show")
     * @Method("GET")
     * @Template()
     */
    // public function showAction($id)
    // {
    //     $em = $this->getDoctrine()->getManager();
    //
    //     $entity = $em->getRepository('AppBundle:Episode')->find($id);
    //
    //     if (!$entity) {
    //         throw $this->createNotFoundException('Unable to find Episode entity.');
    //     }
    //
    //     $deleteForm = $this->createDeleteForm($id);
    //
    //     return array(
    //         'entity'      => $entity,
    //         'delete_form' => $deleteForm->createView(),
    //     );
    // }

    /**
     * Displays a form to edit an existing Episode entity.
     * @Route("/{episode}/edit", name="oktolab_episode_edit")
     * @ParamConverter("episode", class="AppBundle:Episode")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Request $request, $episode)
    {
        $form = $this->createForm(new EpisodeType(), $episode);
        $form->add('submit', 'submit', ['label' => 'oktolab_media.edit_episode_button', 'attr' => ['class' => 'btn btn-primary']]);
        $form->add('delete', 'submit', ['label' => 'oktolab_media.delete_episode_button', 'attr' => ['class' => 'btn btn-danger']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            if ($form->isValid()) { //form is valid, save or preview
                if ($form->get('submit')->isClicked()) { //save me
                    $em->persist($episode);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_edit_episode');
                    return $this->redirect($this->generateUrl('oktolab_episode_show', ['uniqID' => $episode->getUniqID()]));
                } elseif ($form->get('delete')->isClicked()) {
                    $em->remove($episode);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_delete_episode');
                    return $this->redirect($this->generateUrl('backend'));
                } else { //???
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.info_edit_episode_unknown_button');
                    return $this->redirect($this->generateUrl('oktolab_episode_show', ['uniqID' => $episode->getUniqID()]));
                }
            }
            $this->get('session')->getFlashBag()->add('error', 'oktothek.error_edit_episode');
        }

        return ['form' => $form->createView()];
    }

    /**
    * Creates a form to edit a Episode entity.
    *
    * @param Episode $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    // private function createEditForm(Episode $entity)
    // {
    //     $form = $this->createForm(new EpisodeType(), $entity, array(
    //         'action' => $this->generateUrl('episode_update', array('id' => $entity->getId())),
    //         'method' => 'PUT',
    //     ));
    //
    //     $form->add('submit', 'submit', array('label' => 'Update'));
    //
    //     return $form;
    // }
    /**
     * Edits an existing Episode entity.
     *
     * @Route("/{id}", name="episode_update")
     * @Method("PUT")
     * @Template("AppBundle:Episode:edit.html.twig")
     */
    // public function updateAction(Request $request, $id)
    // {
    //     $em = $this->getDoctrine()->getManager();
    //
    //     $entity = $em->getRepository('AppBundle:Episode')->find($id);
    //
    //     if (!$entity) {
    //         throw $this->createNotFoundException('Unable to find Episode entity.');
    //     }
    //
    //     $deleteForm = $this->createDeleteForm($id);
    //     $editForm = $this->createEditForm($entity);
    //     $editForm->handleRequest($request);
    //
    //     if ($editForm->isValid()) {
    //         $em->flush();
    //
    //         return $this->redirect($this->generateUrl('episode_edit', array('id' => $id)));
    //     }
    //
    //     return array(
    //         'entity'      => $entity,
    //         'edit_form'   => $editForm->createView(),
    //         'delete_form' => $deleteForm->createView(),
    //     );
    // }
    /**
     * Deletes a Episode entity.
     *
     * @Route("/{id}", name="episode_delete")
     * @Method("DELETE")
     */
    // public function deleteAction(Request $request, $id)
    // {
    //     $form = $this->createDeleteForm($id);
    //     $form->handleRequest($request);
    //
    //     if ($form->isValid()) {
    //         $em = $this->getDoctrine()->getManager();
    //         $entity = $em->getRepository('AppBundle:Episode')->find($id);
    //
    //         if (!$entity) {
    //             throw $this->createNotFoundException('Unable to find Episode entity.');
    //         }
    //
    //         $em->remove($entity);
    //         $em->flush();
    //     }
    //
    //     return $this->redirect($this->generateUrl('episode'));
    // }

    /**
     * Creates a form to delete a Episode entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    // private function createDeleteForm($id)
    // {
    //     return $this->createFormBuilder()
    //         ->setAction($this->generateUrl('episode_delete', array('id' => $id)))
    //         ->setMethod('DELETE')
    //         ->add('submit', 'submit', array('label' => 'Delete'))
    //         ->getForm()
    //     ;
    // }
}
