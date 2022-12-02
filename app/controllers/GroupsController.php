<?php

namespace controllers;

require_once "../bootstrap.php";

require_once '../models/Group.php';

use Exception;
use models\Group;
use Doctrine\ORM\Exception\ORMException;

class GroupsController
{
    public static function create_group($data): bool
    {
        $gr = new Group();
        $gr->set_g_name($data->g_name);
        try {
            groups_dao()->create_group($gr);
            db_flush();
        } catch (Exception $e) {
            log_err($e);
            return false;
        }
        return true;
    }

    public static function read_all_groups(): ?array
    {
        try {
            $groups = groups_dao()->get_all_groups();
        } catch (Exception $e) {
            log_err($e);
            return null;
        }
        $arr = array();
        foreach ($groups as $gr) {
            $item = array(
                "g_id" => $gr->get_g_id(),
                "g_name" => $gr->get_g_name(),
                "g_tasks_count" => $gr->get_g_tasks_count(),
            );
            array_push($arr, $item);
        }
        return $arr;
    }

    public static function read_group($g_id): ?array
    {
        $gr = groups_dao()->read_group($g_id);
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
        $gr = groups_dao()->read_group($g_id);
        if ($gr == null) {
            return false;
        }
        $gr->set_g_id($g_id);
        $gr->set_g_name($data->g_name);
        try {
            groups_dao()->update_group($gr);
            db_flush();
        } catch (ORMException $e) {
            log_err($e);
            return false;
        }
        return true;
    }

    public static function delete_group($g_id): bool
    {
        try {
            groups_dao()->delete_group($g_id);
            db_flush();
        } catch (ORMException $e) {
            log_err($e);
            return false;
        }
        return true;
    }
}