<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\EpisodePin;
use AppBundle\Form\EpisodePinType;

/**
 * EpisodePin controller.
 *
 * @Route("/episodepin")
 */
class EpisodePinController extends Controller
{

    /**
     * Lists all EpisodePin entities.
     *
     * @Route("/", name="episodepin")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:EpisodePin')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new EpisodePin entity.
     *
     * @Route("/", name="episodepin_create")
     * @Method("POST")
     * @Template("AppBundle:EpisodePin:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new EpisodePin();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('episodepin_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a EpisodePin entity.
     *
     * @param EpisodePin $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(EpisodePin $entity)
    {
        $form = $this->createForm(new EpisodePinType(), $entity, array(
            'action' => $this->generateUrl('episodepin_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new EpisodePin entity.
     *
     * @Route("/new", name="episodepin_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new EpisodePin();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a EpisodePin entity.
     *
     * @Route("/{id}", name="episodepin_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:EpisodePin')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EpisodePin entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing EpisodePin entity.
     *
     * @Route("/{id}/edit", name="episodepin_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:EpisodePin')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EpisodePin entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a EpisodePin entity.
    *
    * @param EpisodePin $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(EpisodePin $entity)
    {
        $form = $this->createForm(new EpisodePinType(), $entity, array(
            'action' => $this->generateUrl('episodepin_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing EpisodePin entity.
     *
     * @Route("/{id}", name="episodepin_update")
     * @Method("PUT")
     * @Template("AppBundle:EpisodePin:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:EpisodePin')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EpisodePin entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('episodepin_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a EpisodePin entity.
     *
     * @Route("/{id}", name="episodepin_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:EpisodePin')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find EpisodePin entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('episodepin'));
    }

    /**
     * Creates a form to delete a EpisodePin entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('episodepin_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
    * @Route("/episodePinStack", name="episodepin_stack")
    * @Template("AppBundle:Default:clipstack.html.twig")
    */
    public function episodePinStackAction()
    {
        $em = $this->getDoctrine()->getManager();
        $episodePins = $em->getRepository('AppBundle:EpisodePin')->findBy(array(), array('onlineAt' => 'ASC'), 5, 0);

        return array('pins' => $episodePins);
    }
}
