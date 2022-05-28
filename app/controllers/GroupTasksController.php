<?php

namespace controllers;

require_once "../bootstrap.php";

require_once '../dal/models/Task.php';

use dal\models\Task;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

class GroupTasksController
{
    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public static function createTask($g_id, $data)
    {
        $t = new Task();
        $t->set_g_id($g_id);
        $t_date = date("Y-m-d H:i:s");
        $t->set_t_date($t_date);
        $t->set_t_subject($data->t_subject);
        $t->set_t_priority(1);
        $t->set_t_comments("");
        em()->persist($t);
        em()->flush();
    }

    public static function readGroupTasks($g_id): ?array
    {
        try {
            $tasks = tasks()->findBy(array('g_id' => $g_id), array('t_date' => 'ASC', 't_id' => 'ASC'));
        } catch (ORMException $e) {
            echo $e->getMessage();
            return null;
        }
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