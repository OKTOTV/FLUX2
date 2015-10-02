<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Bprs\UserBundle\Entity\User as BaseUser;
/**
 * IntakeUser
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Bprs\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
}
