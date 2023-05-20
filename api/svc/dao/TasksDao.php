<?php

namespace svc\dao;

include_once __DIR__ . '/../models/TaskLi.php';
include_once __DIR__ . '/TasksDaoGenerated.php';

use svc\models\TaskLI;

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
    function get_project_tasks($p_id): array
    {
        return $this->taskLI()->findBy(array('p_id' => $p_id), array('t_date' => 'ASC', 't_id' => 'ASC'));
    }
}
