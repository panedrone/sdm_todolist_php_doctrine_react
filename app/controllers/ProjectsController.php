<?php

namespace controllers;

require_once "../bootstrap.php";

require_once '../models/Project.php';

use models\Project;

class ProjectsController
{
    /**
     * @throws \Exception
     */
    public static function create_project($data)
    {
        $pr = new Project();
        $pr->set_p_name($data->p_name);
        projects_dao()->create_project($pr);
        db_flush();
    }

    /**
     * @throws \Exception
     */
    public static function read_projects(): array
    {
        $projects = projects_dao()->get_projects();
        if ($projects == null) {
            return array();
        }
        $arr = array();
        foreach ($projects as $pr) {
            $item = array(
                "p_id" => $pr->get_p_id(),
                "p_name" => $pr->get_p_name(),
                "p_tasks_count" => $pr->get_p_tasks_count(),
            );
            array_push($arr, $item);
        }
        return $arr;
    }

    /**
     * @throws \Exception
     */
    public static function read_project($g_id): array
    {
        $pr = projects_dao()->read_project($g_id);
        if ($pr == null) {
            return array();
        }
        $item = array(
            "p_id" => $pr->get_p_id(),
            "p_name" => $pr->get_p_name(),
        );
        return $item;
    }

    /**
     * @throws \Exception
     */
    public static function update_project($p_id, $data): bool
    {
        $gr = projects_dao()->read_project($p_id);
        if ($gr == null) {
            return false;
        }
        $gr->set_p_id($p_id);
        $gr->set_p_name($data->p_name);
        projects_dao()->update_project($gr);
        db_flush();
        return true;
    }

    /**
     * @throws \Exception
     */
    public static function delete_project($p_id)
    {
        projects_dao()->delete_project($p_id);
        db_flush();
    }
}