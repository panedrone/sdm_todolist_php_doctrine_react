<?php

namespace controllers;

require_once "../bootstrap.php";

require_once '../models/Task.php';

use models\Task;

class GroupTasksController
{
    public static function create_task($g_id, $data): bool
    {
        $t = new Task();
        $t->set_g_id($g_id);
        $t_date = date("Y-m-d H:i:s");
        $t->set_t_date($t_date);
        $t->set_t_subject($data->t_subject);
        $t->set_t_priority(1);
        $t->set_t_comments("");
        try {
            tasks_dao()->create_task($t);
            db_flush();
        } catch (\Exception $e) {
            log_err($e);
            return false;
        }
        return true;
    }

    public static function read_group_tasks($g_id): ?array
    {
        $tasks = tasks_dao()->get_group_tasks($g_id);
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
}