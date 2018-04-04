<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateTrendingScoreCommand extends ContainerAwareCommand {

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('homepage:media:update_trending_score')
            ->setDescription('Updates the trending score of episodes according to the current views.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $episode_service = $this->getContainer()->get('oktothek_episode');
        $episode_service->updateTrendingScore();
    }
}
