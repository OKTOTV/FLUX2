<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/newsletter")
 */
class NewsletterController extends Controller {

    /**
     * @Route("/subscribe", name="oktothek_newsletter")
     * @Template()
     */
    public function subscribeAction(Request $request)
    {
        $data = ['email' => ''];
        $form = $this->get('form.factory')->createNamedBuilder('newsletterform', FormType::class, $data, [])
            ->setAction($this->generateUrl('oktothek_newsletter'))
            ->add('email', EmailType::class, ['constraints' => [new Email(['checkMX' => true]), new NotBlank()], 'attr' => ['placeholder' => 'oktothek.newsletter_mail_placeholder']])
            ->add('confirm', CheckboxType::class, ['constraints' => new IsTrue(), 'label' => 'oktothek.newsletter_confirm_label'])
            ->add('submit', SubmitType::class, ['label' => "oktothek.newsletter_subscribe_submit"])
            ->getForm();

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $email = $form->getData()['email'];
                $subject = $this->get('translator')->trans('oktothek.confirm_newsletter_subject');
                $success = $this->get('oktothek_newsletter')->subscribe($email);
                if ($success) {
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.success_subscribe_newsletter');
                } else {
                    $this->get('session')->getFlashBag()->add('error', 'oktothek.error_subscribe_newsletter');
                }
                return $this->redirect($request->headers->get('referer'));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'oktothek.error_submit_newsletter');
                return $this->redirect($request->headers->get('referer'));
            }
        }
        return ['newsletterform' => $form->createView()];
    }
}
