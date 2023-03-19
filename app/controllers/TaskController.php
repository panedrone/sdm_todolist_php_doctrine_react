<?php

namespace controllers;

require_once "../bootstrap.php";

require_once "../models/Task.php";

class TaskController
{
    /**
     * @throws \Exception
     */
    public static function read_task($t_id): ?array
    {
        $t = tasks_dao()->read_task($t_id);
        if ($t == null) {
            return null;
        }
        $item = array(
            "t_id" => $t->get_t_id(),
            "t_subject" => $t->get_t_subject(),
            "t_date" => $t->get_t_date(),
            "t_priority" => $t->get_t_priority(),
            "t_comments" => $t->get_t_comments(),
        );
        return $item;
    }

    /**
     * @throws \Exception
     */
    public static function update_task($t_id, $data): bool
    {
        $t = tasks_dao()->read_task($t_id);
        if ($t == null) {
            return false;
        }
        $t->set_t_date($data->t_date);
        $t->set_t_subject($data->t_subject);
        $t->set_t_priority($data->t_priority);
        $t->set_t_comments($data->t_comments);
        tasks_dao()->update_task($t);
        db_flush();
        return true;
    }

    /**
     * @throws \Exception
     */
    public static function delete_task($t_id)
    {
        tasks_dao()->delete_task($t_id);
        db_flush();
    }
}