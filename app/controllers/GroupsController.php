<?php

namespace controllers;

require_once "../bootstrap.php";

require_once '../models/Group.php';

use models\Group;
use Doctrine\ORM\Exception\ORMException;

class GroupsController
{
    public static function create_group($data): bool
    {
        $gr = new Group();
        $gr->set_g_name($data->g_name);
        try {
            em()->persist($gr);
            em()->flush();
        } catch (ORMException $e) {
            log_err($e);
            return false;
        }
        return true;
    }

    public static function read_groups(): ?array
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

    public static function read_group($g_id): ?array
    {
        $gr = find_group($g_id);
        if ($gr == null) {
            return null;
        }
        $item = array(
            "g_id" => $gr->get_g_id(),
            "g_name" => $gr->get_g_name(),
        );
        return $item;
    }

    public static function update_group($g_id, $data): bool
    {
        $gr = find_group($g_id);
        if ($gr == null) {
            return false;
        }
        $gr->set_g_id($g_id);
        $gr->set_g_name($data->g_name);
        try {
            em()->persist($gr);
            em()->flush();
        } catch (ORMException $e) {
            log_err($e);
            return false;
        }
        return true;
    }

    public static function delete_group($g_id): bool
    {
        $gr = em()->getPartialReference(Group::class, $g_id);
        if ($gr == null) {
            return false;
        }
        try {
            em()->remove($gr);
            em()->flush();
        } catch (ORMException $e) {
            log_err($e);
            return false;
        }
        return true;
    }
}