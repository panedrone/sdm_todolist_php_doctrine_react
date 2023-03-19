<?php

namespace api;

require_once 'utils.php';

require_once '../controllers/GroupsController.php';
require_once '../controllers/GroupTasksController.php';
require_once '../controllers/TaskController.php';

use controllers\GroupsController;
use controllers\GroupTasksController;
use controllers\TaskController;

$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_SPECIAL_CHARS);

function handle_groups()
{
    global $method;
    if ($method == "POST") {
        $data = json_decode(file_get_contents("php://input"));
        if (json_last_error() != JSON_ERROR_NONE) {
            http_response_code(HTTP_BAD_REQUEST);
            return;
        }
        if (!validate_group($data)) {
            http_response_code(HTTP_BAD_REQUEST);
            return;
        }
        if (GroupsController::create_group($data)) {
            http_response_code(HTTP_CREATED);
        } else {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
        }
    } else if ($method == "GET") {
        $arr = GroupsController::read_all_groups();
        if ($arr === null) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
        } else {
            json_resp($arr);
        }
    }
}

function validate_group($data)
{
    return true; // TODO
}

function handle_group($g_id)
{
    global $method;
    if ($method == "GET") {
        $item = GroupsController::read_group($g_id);
        if ($item === null) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
        } else {
            json_resp($item);
        }
    } else if ($method == "PUT") {
        $data = json_decode(file_get_contents("php://input"));
        if (json_last_error() != JSON_ERROR_NONE) {
            http_response_code(HTTP_BAD_REQUEST);
            return;
        }
        if (!validate_group($data)) {
            http_response_code(HTTP_BAD_REQUEST);
            return;
        }
        if (!GroupsController::update_group($g_id, $data)) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
        }
    } else if ($method == "DELETE") {
        if (GroupsController::delete_group($g_id)) {
            http_response_code(HTTP_NO_CONTENT);
        } else {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

function handle_group_tasks($g_id)
{
    global $method;
    if ($method == "POST") {
        $data = json_decode(file_get_contents("php://input"));
        if (json_last_error() != JSON_ERROR_NONE) {
            http_response_code(HTTP_BAD_REQUEST);
            return;
        }
        if (!validate_task($data)) {
            http_response_code(HTTP_BAD_REQUEST);
            return;
        }
        if (GroupTasksController::create_task($g_id, $data)) {
            http_response_code(HTTP_CREATED);
        } else {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
        }
    } else if ($method == "GET") {
        $arr = GroupTasksController::read_group_tasks($g_id);
        if ($arr === null) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
        } else {
            json_resp($arr);
        }
    }
}

function validate_task($data)
{
    return true; // TODO
}

function handle_task($t_id)
{
    global $method;
    if ($method == "GET") {
        $item = TaskController::read_task($t_id);
        if ($item === null) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
        } else {
            json_resp($item);
        }
    } else if ($method == "PUT") {
        $data = json_decode(file_get_contents("php://input"));
        if (json_last_error() != JSON_ERROR_NONE) {
            http_response_code(HTTP_BAD_REQUEST);
            return;
        }
        if (!validate_task($data)) {
            http_response_code(HTTP_BAD_REQUEST);
            return;
        }
        if (!TaskController::update_task($t_id, $data)) {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
        }
    } else if ($method == "DELETE") {
        if (TaskController::delete_task($t_id)) {
            http_response_code(HTTP_NO_CONTENT);
        } else {
            http_response_code(HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

