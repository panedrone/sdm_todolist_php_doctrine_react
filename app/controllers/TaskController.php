<?php

namespace controllers;

//require_once "../bootstrap.php";
//
//require_once "../dal/models/TaskEx.php";

use dal\models\TaskEx;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

class TaskController
{
    /**
     * @throws ORMException
     */
    public static function readTask($t_id): ?array
    {
        $t = task_ex()->find($t_id);
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
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public static function updateTask($t_id, $data): bool
    {
        $t = task_ex()->find($t_id);
        if ($t == null) {
            return false;
        }
        $t->set_t_date($data->t_date);
        $t->set_t_subject($data->t_subject);
        $t->set_t_priority($data->t_priority);
        $t->set_t_comments($data->t_comments);
        em()->persist($t);
        em()->flush();
        return true;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public static function deleteTask($t_id): bool
    {
        $t = em()->getPartialReference(TaskEx::class, $t_id);
        if ($t == null) {
            return false;
        }
        em()->remove($t);
        em()->flush();
        return true;
    }
}