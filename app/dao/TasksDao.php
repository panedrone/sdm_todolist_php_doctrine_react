<?php

namespace dao;

include_once __DIR__ . '/../models/TaskLI.php';

use models\TaskLI;

class TasksDao extends TasksDaoGenerated
{
    public function __construct($ds)
    {
        parent::__construct($ds);
    }

    private function taskLI()
    {
        return $this->ds->em()->getRepository(TaskLI::class);
    }

    /**
     * @return TaskLI[]
     */
    function get_group_tasks($g_id): array
    {
        return $this->taskLI()->findBy(array('g_id' => $g_id), array('t_date' => 'ASC', 't_id' => 'ASC'));
    }
}
