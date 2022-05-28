<?php

namespace controllers;

require_once "../bootstrap.php";

require_once '../dal/models/Group.php';

use dal\models\Group;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

class GroupsController
{
    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public static function createGroup($data)
    {
        $gr = new Group();
        $gr->set_g_name($data->g_name);
        em()->persist($gr);
        em()->flush();
    }

    public static function readGroups(): array
    {
        $groups = groups_dao()->get_groups();
        $arr = array();
        foreach ($groups as $gr) {
            $item = array(
                "g_id" => $gr->get_g_id(),
                "g_name" => $gr->get_g_name(),
                "tasks_count" => $gr->get_tasks_count(),
            );
            array_push($arr, $item);
        }
        return $arr;
    }

    /**
     * @throws ORMException
     */
    public static function readGroup($g_id): ?array
    {
        $gr = groups()->find($g_id);
        if ($gr == null) {
            return null;
        }
        $item = array(
            "g_id" => $gr->get_g_id(),
            "g_name" => $gr->get_g_name(),
        );
        return $item;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public static function updateGroup($g_id, $data): bool
    {
        $gr = groups()->find($g_id);
        if ($gr == null) {
            return false;
        }
        $gr->set_g_id($g_id);
        $gr->set_g_name($data->g_name);
        em()->persist($gr);
        em()->flush();
        return true;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public static function deleteGroup($g_id): bool
    {
        $gr = em()->getPartialReference(Group::class, $g_id);
        if ($gr == null) {
            return false;
        }
        em()->remove($gr);
        em()->flush();
        return true;
    }
}