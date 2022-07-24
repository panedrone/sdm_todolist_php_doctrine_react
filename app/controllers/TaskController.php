<?php

namespace controllers;

require_once "../bootstrap.php";

require_once "../models/Task.php";

use models\Task;
use Doctrine\ORM\Exception\ORMException;

class TaskController
{
    public static function read_task($t_id): ?array
    {
        $t = find_task($t_id);
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

    public static function update_task($t_id, $data): bool
    {
        $t = find_task($t_id);
        if ($t == null) {
            return false;
        }
        $t->set_t_date($data->t_date);
        $t->set_t_subject($data->t_subject);
        $t->set_t_priority($data->t_priority);
        $t->set_t_comments($data->t_comments);
        try {
            em()->persist($t);
            em()->flush();
        } catch (ORMException $e) {
            log_err($e);
            return false;
        }
        return true;
    }

    public static function delete_task($t_id): bool
    {
        $t = em()->getPartialReference(Task::class, $t_id);
        if ($t == null) {
            return false;
        }
        try {
            em()->remove($t);
            em()->flush();
        } catch (ORMException $e) {
            log_err($e);
            return false;
        }
        return true;
    }
}