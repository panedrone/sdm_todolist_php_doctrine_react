<?php

namespace dal;

// This code was generated by a tool. Don't modify it manually.
// http://sqldalmaker.sourceforge.net

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="groups")
 */
class Group
{
    /**
     * @ORM\Column(name="g_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     * @var int
     */
    private $gId;
    /**
     * @ORM\Column(name="g_name", type="string", length=65535, unique=true)
     * @var string
     */
    private $gName;
    /**
     * @ORM\Column(name="g_comments", type="string", length=65535, nullable=true)
     * @var string
     */
    private $gComments;

    public function getGId()
    {
        return $this->gId;
    }

    public function setGId($value)
    {
        $this->gId = $value;
    }

    public function getGName()
    {
        return $this->gName;
    }

    public function setGName($value)
    {
        $this->gName = $value;
    }

    public function getGComments()
    {
        return $this->gComments;
    }

    public function setGComments($value)
    {
        $this->gComments = $value;
    }
}