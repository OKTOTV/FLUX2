<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class SendDataUsageConfirmationMailTestCommand extends ContainerAwareCommand {

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('homepage:dsgvo:send_data_usage_confirmation_mail_test')
            ->setDescription('Sends out an email with link to confirm data usage of given user account')
            ->addArgument('user', InputArgument::REQUIRED, 'username you want to send mail to');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $user = $em->getRepository('AppBundle:User')->findOneBy(
            ['username' => $input->getArgument('user')]
        );
        $mailer = $this->getContainer()->get('bprs_user.mailer');

        $context = $this->getContainer()->get('router')->getContext();
        $context->setHost($this->getContainer()->getParameter('bprs_user.mail.commandline_host'));

        $mailer->sendMail(
            $user->getEmail(),
            'AppBundle:mail:data_usage_confirmation.html.twig',
            ['user' => $user],
            'OKTO Datenschutzerklärung'
        );

        $output->writeln(sprintf('Sent out %s mails!', count($user)));
    }
}
