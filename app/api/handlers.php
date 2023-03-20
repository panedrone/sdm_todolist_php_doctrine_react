<?php

namespace api;

require_once 'utils.php';

require_once __DIR__ . '/../controllers/GroupsController.php';
require_once __DIR__ . '/../controllers/GroupTasksController.php';
require_once __DIR__ . '/../controllers/TaskController.php';
require_once './validators.php';

use controllers\GroupsController;
use controllers\GroupTasksController;
use controllers\TaskController;
use Exception;

$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_SPECIAL_CHARS);

function handle_groups()
{
    try {
        global $method;
        if ($method == "POST") {
            $data = json_decode(file_get_contents("php://input"));
            if (json_last_error() != JSON_ERROR_NONE) {
                http_response_code(HTTP_BAD_REQUEST);
                return;
            }
            $err = validate_group($data);
            if ($err != null) {
                json_resp($err);
                http_response_code(HTTP_BAD_REQUEST);
                return;
            }
            GroupsController::create_group($data);
            http_response_code(HTTP_CREATED);
        } else if ($method == "GET") {
            $arr = GroupsController::read_groups();
            json_resp($arr);
        }
    } catch (Exception $e) {
        http_response_code(HTTP_INTERNAL_SERVER_ERROR);
    }
}

function handle_group($g_id)
{
    try {
        global $method;
        if ($method == "GET") {
            $item = GroupsController::read_group($g_id);
            json_resp($item);
        } else if ($method == "PUT") {
            $data = json_decode(file_get_contents("php://input"));
            if (json_last_error() != JSON_ERROR_NONE) {
                http_response_code(HTTP_BAD_REQUEST);
                return;
            }
            $err = validate_group($data);
            if ($err != null) {
                json_resp($err);
                http_response_code(HTTP_BAD_REQUEST);
                return;
            }
            if (!GroupsController::update_group($g_id, $data)) {
                http_response_code(HTTP_BAD_REQUEST);
                return;
            }
        } else if ($method == "DELETE") {
            GroupsController::delete_group($g_id);
            http_response_code(HTTP_NO_CONTENT);
        }
    } catch (Exception $e) {
        http_response_code(HTTP_INTERNAL_SERVER_ERROR);
    }
}

function handle_group_tasks($g_id)
{
    try {
        global $method;
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
            GroupTasksController::create_task($g_id, $data);
            http_response_code(HTTP_CREATED);
        } else if ($method == "GET") {
            $arr = GroupTasksController::read_group_tasks($g_id);
            json_resp($arr);
        }
    } catch (Exception $e) {
        http_response_code(HTTP_INTERNAL_SERVER_ERROR);
    }
}

function handle_task($t_id)
{
    try {
        global $method;
        if ($method == "GET") {
            $item = TaskController::read_task($t_id);
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
            if (!TaskController::update_task($t_id, $data)) {
                http_response_code(HTTP_BAD_REQUEST);
                return;
            }
        } else if ($method == "DELETE") {
            TaskController::delete_task($t_id);
            http_response_code(HTTP_NO_CONTENT);
        }
    } catch (Exception $e) {
        http_response_code(HTTP_INTERNAL_SERVER_ERROR);
    }
}

