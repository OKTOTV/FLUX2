<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateAndUpdateYearlyTagsCommand extends ContainerAwareCommand {

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('homepage:media:create_and_update_yearly_tags')
            ->setDescription('updates year tags (2005, 2006, 2007, etc) and adds episodes according to their respective firstranAt year');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tag_service = $this->getContainer()->get('okto_media_tag');
        $year_tags = [];
        $current_year = (new \DateTime())->format('Y');
        for ($i=0; $i <= $current_year; $i++) {
            if ($tag = $tag_service->getTagByText((string)$i)) {
                $year_tags[$i] = $tag;
            }
        }
        $oktolab_media = $this->getContainer()->get('oktolab_media');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        foreach ($year_tags as $year => $tag) {
            $output->writeln('Start adding episodes for year '.$year);
            $episodes = $oktolab_media->getEpisodeRepository()->findEpisodesByFirstRanAtYear($year);
            $added = 0;
            foreach ($episodes as $episode) {
                if (!in_array($tag, $episode->getTags()->toArray())) {
                    $episode->addTag($tag);
                    $em->persist($episode);
                    $em->flush();
                    $added++;
                }
            }
            $output->writeln(sprintf('Tagged Episodes: %s', count($episodes)));
            $output->writeln(sprintf('Added Episodes: %s', $added));
        }
    }
}
