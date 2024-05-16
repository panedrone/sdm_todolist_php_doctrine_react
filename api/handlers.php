<?php

namespace api;

require_once __DIR__ . '/svc/SvcProjects.php';
require_once __DIR__ . '/svc/SvcProjectTasks.php';
require_once __DIR__ . '/svc/SvcTasks.php';

require_once __DIR__ . '/validators.php';
require_once __DIR__ . '/utils.php';

use Exception;

function handle_projects()
{
    try {
        $method = get_request_method();
        if ($method == "POST") {
            $data = get_json();
            if (json_last_error() != JSON_ERROR_NONE) {
                http_response_code(HTTP_BAD_REQUEST);
                return;
            }
            $err = validate_project($data);
            if ($err != null) {
                json_resp($err);
                http_response_code(HTTP_BAD_REQUEST);
                return;
            }
            project_create($data);
            http_response_code(HTTP_CREATED);
        } else if ($method == "GET") {
            $arr = projects_read_all();
            json_resp($arr);
        }
    } catch (Exception $e) {
        http_response_code(HTTP_INTERNAL_SERVER_ERROR);
    }
}

function handle_project($g_id)
{
    try {
        $method = get_request_method();
        if ($method == "GET") {
            $item = project_read($g_id);
            json_resp($item);
        } else if ($method == "PUT") {
            $data = get_json();
            if (json_last_error() != JSON_ERROR_NONE) {
                http_response_code(HTTP_BAD_REQUEST);
                return;
            }
            $err = validate_project($data);
            if ($err != null) {
                json_resp($err);
                http_response_code(HTTP_BAD_REQUEST);
                return;
            }
            if (!project_update($g_id, $data)) {
                http_response_code(HTTP_BAD_REQUEST);
                return;
            }
        } else if ($method == "DELETE") {
            project_delete($g_id);
            http_response_code(HTTP_NO_CONTENT);
        }
    } catch (Exception $e) {
        http_response_code(HTTP_INTERNAL_SERVER_ERROR);
    }
}

function handle_project_tasks($g_id)
{
    try {
        $method = get_request_method();
        if ($method == "POST") {
            $data = get_json();
            if (json_last_error() != JSON_ERROR_NONE) {
                http_response_code(HTTP_BAD_REQUEST);
                return;
            }
            $err = validate_task($data, false);
            if ($err != null) {
                json_resp($err);
                http_response_code(HTTP_BAD_REQUEST);
                return;
            }
            project_task_create($g_id, $data);
            http_response_code(HTTP_CREATED);
        } else if ($method == "GET") {
            $arr = project_tasks_read($g_id);
            json_resp($arr);
        }
    } catch (Exception $e) {
        http_response_code(HTTP_INTERNAL_SERVER_ERROR);
    }
}

function handle_task($t_id)
{
    try {
        $method = get_request_method();
        if ($method == "GET") {
            $item = read_task($t_id);
            json_resp($item);
        } else if ($method == "PUT") {
            $data = get_json();
            if (json_last_error() != JSON_ERROR_NONE) {
                http_response_code(HTTP_BAD_REQUEST);
                return;
            }
            $err = validate_task($data, true);
            if ($err != null) {
                json_resp($err);
                http_response_code(HTTP_BAD_REQUEST);
                return;
            }
            if (!update_task($t_id, $data)) {
                http_response_code(HTTP_BAD_REQUEST);
                return;
            }
        } else if ($method == "DELETE") {
            delete_task($t_id);
            http_response_code(HTTP_NO_CONTENT);
        }
    } catch (Exception $e) {
        http_response_code(HTTP_INTERNAL_SERVER_ERROR);
    }
}

