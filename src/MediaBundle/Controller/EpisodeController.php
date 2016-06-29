<?php

namespace MediaBundle\Controller;

use Oktolab\MediaBundle\Controller\EpisodeController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use MediaBundle\Entity\Episode;
use MediaBundle\Form\EpisodeType;
use MediaBundle\Form\PublishEpisodeType;
use AppBundle\Entity\Notification;

/**
 * Episode controller.
 *
 * @Route("/oktolab_media/episode")
 */
class EpisodeController extends BaseController
{
    /**
     * Displays a form to edit an existing Episode entity.
     * @Route("/{episode}/edit", name="oktolab_episode_edit")
     * @ParamConverter("episode", class="MediaBundle:Episode")
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
                    $this->get('session')->getFlashBag()->add('success', 'oktolab_media.success_edit_episode');
                    return $this->redirect($this->generateUrl('oktolab_episode_show', ['uniqID' => $episode->getUniqID()]));
                } elseif ($form->get('delete')->isClicked()) {
                    $em->remove($episode);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktolab_media.success_delete_episode');
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
}
