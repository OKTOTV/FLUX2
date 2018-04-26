<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendDataUsageConfirmationMailCommand extends ContainerAwareCommand {

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('homepage:dsgvo:send_data_usage_confirmation_mail')
            ->setDescription('Sends out an email with link to confirm data usage of okto user accounts');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $users = $em->getRepository('AppBundle:User')->findBy(['confirmed_data_usage' => false]);
        $mailer = $this->getContainer()->get('bprs_user.mailer');

        $context = $this->getContainer()->get('router')->getContext();
        $context->setHost($this->getContainer()->getParameter('bprs_user.mail.commandline_host'));

        foreach ($users as $user) {
            $mailer->sendMail(
                $user->getEmail(),
                'AppBundle:mail:data_usage_confirmation.html.twig',
                ['user' => $user],
                'OKTO Datenschutzerklärung'
            );
        }

        $output->writeln(sprintf('Sent out %s mails!', count($users)));
    }
}
