<?php

namespace dal;

// This code was generated by a tool. Don't modify it manually.
// http://sqldalmaker.sourceforge.net

class GroupEx
{
    /**
     * @var object
     */
    private $gId;
    /**
     * @var object
     */
    private $gName;
    /**
     * @var object
     */
    private $gComments;
    /**
     * @var object
     */
    private $tasksCount;

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

    public function getTasksCount()
    {
        return $this->tasksCount;
    }

    public function setTasksCount($value)
    {
        $this->tasksCount = $value;
    }
}