<?php

namespace AppBundle\Twig;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CommentExtension extends \Twig_Extension
{
    private $url_generator;
    private $uniqID;

    public function __construct($urlgenerator)
    {
        $this->url_generator = $urlgenerator;
    }

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('episodeComment', array($this, 'episodeCommentFilter'))
        );
    }

    /**
     * replaces all d?:d? timestamps with a <a href="" class="skipper">d?:d?</a>
     * in a comment
     */
    public function episodeCommentFilter($comment, $uniqID)
    {
        $pattern = '((\d{1,2}:)?\d{1,2}:\d{2}?)'; // timestamp like 3:10:45, 06:40:3, 1:35
        // TODO: generate Episode url absolute;
        $this->uniqID = $uniqID;
        $commentFiltered = preg_replace_callback($pattern, [$this, 'callbackSkipReplace'], $comment);
        return $commentFiltered;
    }

    public function callbackSkipReplace($matches)
    {
        $timestamp_parts = explode(':', $matches[0]);
        $seconds = 0;
        if (count($timestamp_parts) > 2) { // contains hours
            $hours = 3600 * $timestamp_parts[0];
            $minutes = 60 * $timestamp_parts[1];
            $seconds = $timestamp_parts[2] + $minutes + $hours;
        } else {
            $seconds = $timestamp_parts[1] + 60 * $timestamp_parts[0];
        }
        $url = $this->url_generator->generate('oktothek_show_episode', ['uniqID' => $this->uniqID, 'start' => $seconds], UrlGeneratorInterface::ABSOLUTE_URL);
        return sprintf('<a href="%s" class="skipper" data-second="%s">%s</a>', $url, $seconds, $matches[0]);
    }

    public function getName() {
        return 'oktothek_comment_extension';
    }
}
