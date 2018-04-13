<?php

namespace AppBundle\Entity;

interface CommentInterface
{
    public function removeComment($comment);
    public function addComment($comment);
}

 ?>
