<?php

namespace api;

require_once __DIR__ . '/svc/SvcProjects.php';
require_once __DIR__ . '/svc/SvcProjectTasks.php';
require_once __DIR__ . '/svc/SvcTasks.php';

require_once __DIR__ . '/validators.php';
require_once __DIR__ . '/utils.php';

use Exception;
use svc\SvcProjects;
use svc\SvcProjectTasks;
use svc\SvcTasks;

function handle_projects()
{
    try {
        $method = get_request_method();
        if ($method == "POST") {
            $data = json_decode(file_get_contents("php://input"));
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
            SvcProjects::create_project($data);
            http_response_code(HTTP_CREATED);
        } else if ($method == "GET") {
            $arr = SvcProjects::read_projects();
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
            $item = SvcProjects::read_project($g_id);
            json_resp($item);
        } else if ($method == "PUT") {
            $data = json_decode(file_get_contents("php://input"));
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
            if (!SvcProjects::update_project($g_id, $data)) {
                http_response_code(HTTP_BAD_REQUEST);
                return;
            }
        } else if ($method == "DELETE") {
            SvcProjects::delete_project($g_id);
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
            $data = json_decode(file_get_contents("php://input"));
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
            SvcProjectTasks::create_task($g_id, $data);
            http_response_code(HTTP_CREATED);
        } else if ($method == "GET") {
            $arr = SvcProjectTasks::read_project_tasks($g_id);
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
            $item = SvcTasks::read_task($t_id);
            json_resp($item);
        } else if ($method == "PUT") {
            $data = json_decode(file_get_contents("php://input"));
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
            if (!SvcTasks::update_task($t_id, $data)) {
                http_response_code(HTTP_BAD_REQUEST);
                return;
            }
        } else if ($method == "DELETE") {
            SvcTasks::delete_task($t_id);
            http_response_code(HTTP_NO_CONTENT);
        }
    } catch (Exception $e) {
        http_response_code(HTTP_INTERNAL_SERVER_ERROR);
    }
}

