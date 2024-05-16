<?php

require_once __DIR__ . "/../bootstrap.php";

require_once __DIR__ . '/models/Project.php';

use svc\models\Project;

/**
 * @throws Exception
 */
function project_create($data)
{
    $pr = new Project();
    $pr->set_p_name($data->p_name);
    projects_dao()->create_project($pr);
    db_flush();
}

/**
 * @throws Exception
 */
function projects_read_all(): array
{
    $projects = projects_dao()->get_projects();
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
 * @throws Exception
 */
function project_read($p_id): array
{
    $pr = projects_dao()->read_project($p_id);
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
 * @throws Exception
 */
function project_update($p_id, $data): bool
{
    $pr = projects_dao()->read_project($p_id);
    if ($pr == null) {
        return false;
    }
    $pr->set_p_id($p_id);
    $pr->set_p_name($data->p_name);
    projects_dao()->update_project($pr);
    db_flush();
    return true;
}

/**
 * @throws Exception
 */
function project_delete($p_id)
{
    projects_dao()->delete_project($p_id);
    db_flush();
}
