<?php

namespace controllers;

require_once "../bootstrap.php";

require_once '../models/Group.php';

use models\Group;

class GroupsController
{
    /**
     * @throws \Exception
     */
    public static function create_group($data)
    {
        $gr = new Group();
        $gr->set_g_name($data->g_name);
        groups_dao()->create_group($gr);
        db_flush();
    }

    /**
     * @throws \Exception
     */
    public static function read_groups(): array
    {
        $groups = groups_dao()->get_all_groups();
        if ($groups == null) {
            return array();
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

    /**
     * @throws \Exception
     */
    public static function read_group($g_id): array
    {
        $gr = groups_dao()->read_group($g_id);
        if ($gr == null) {
            return array();
        }
        $item = array(
            "g_id" => $gr->get_g_id(),
            "g_name" => $gr->get_g_name(),
        );
        return $item;
    }

    /**
     * @throws \Exception
     */
    public static function update_group($g_id, $data): bool
    {
        $gr = groups_dao()->read_group($g_id);
        if ($gr == null) {
            return false;
        }
        $gr->set_g_id($g_id);
        $gr->set_g_name($data->g_name);
        groups_dao()->update_group($gr);
        db_flush();
        return true;
    }

    /**
     * @throws \Exception
     */
    public static function delete_group($g_id)
    {
        groups_dao()->delete_group($g_id);
        db_flush();
    }
}