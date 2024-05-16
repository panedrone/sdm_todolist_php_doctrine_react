<?php

require_once __DIR__ . "/../bootstrap.php";

require_once __DIR__ . '/models/Task.php';

use svc\models\Task;


/**
 * @throws \Exception
 */
function project_task_create($p_id, $data)
{
    $t = new Task();
    $t->set_p_id($p_id);
    $t_date = date("Y-m-d");
    $t->set_t_date($t_date);
    $t->set_t_subject($data->t_subject);
    $t->set_t_priority(1);
    $t->set_t_comments("");
    tasks_dao()->create_task($t);
    db_flush();
}

/**
 * @throws \Exception
 */
function project_tasks_read($p_id): array
{
    $tasks = tasks_dao()->get_project_tasks($p_id);
    if ($tasks == null) {
        return array();
    }
    $arr = array();
    foreach ($tasks as $t) {
        $item = array(
            "t_id" => $t->get_t_id(),
            "t_subject" => $t->get_t_subject(),
            "t_date" => $t->get_t_date(),
            "t_priority" => $t->get_t_priority(),
        );
        array_push($arr, $item);
    }
    return $arr;
}
