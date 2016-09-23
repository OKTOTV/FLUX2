<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

/**
 * @Route("/newsletter")
 */
class NewsletterController extends Controller {

    /**
     * @Route("/{newsletter}/subscribe", name="oktothek_newsletter")
     * @Template()
     */
    public function subscribeAction(Request $request, $newsletter)
    {
        $data = ['email' => ''];
        $form = $this->createFormBuilder($data)
            ->setAction($this->generateUrl('oktothek_newsletter', ['newsletter' => $newsletter]))
            ->add('email', EmailType::class, ['attr' => ['placeholder' => 'oktothek.newsletter_mail_placeholder']])
            ->add('submit', SubmitType::class, ['label' => "oktothek.newsletter_subscribe_submit"])
            ->getForm();

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $email = $form->getData()['email'];
                $success = $this->get('oktothek_newsletter')->subscribe($email, $newsletter);
                if ($success) {
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_subscribe_newsletter');
                } else {
                    $this->get('session')->getFlashBag()->add('error', 'oktothek.error_subscribe_newsletter');
                }
                return $this->redirect($request->headers->get('referer'));
            }
        }
        return ['form' => $form->createView()];
    }

}
