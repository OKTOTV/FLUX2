<?php

namespace AppBundle\Twig;

class CommentExtension extends \Twig_Extension
{
    private $url;

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('episodeComment', array($this, 'episodeCommentFilter'))
        );
    }

    /**
     * replaces all d?:d? timestamps with a <a href="" class="skipper">d?:d?</a>
     * in a comment
     */
    public function episodeCommentFilter($comment, $url = '#')
    {
        $pattern = '((\d{1,2}:)?\d{1,2}:\d{2}?)'; // timestamp like 3:10:45, 06:40:3, 1:35
        $this->url = $url;
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

        return sprintf('<a href="%s" class="skipper" data-second="%s">%s</a>', $this->url, $seconds, $matches[0]);
    }

    public function getName() {
        return 'oktothek_comment_extension';
    }
}
