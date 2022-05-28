<?php

namespace api;

require_once '../controllers/GroupsController.php';
require_once '../controllers/GroupTasksController.php';
require_once '../controllers/TaskController.php';

require_once 'utils.php';

use controllers\GroupsController;
use controllers\GroupTasksController;
use controllers\TaskController;

$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_SPECIAL_CHARS);

function handle_groups()
{
    global $method;
    if ($method == "POST") {
        $data = json_decode(file_get_contents("php://input"));
        GroupsController::createGroup($data);
        http_response_code(HTTP_CREATED);
    } else if ($method == "GET") {
        $arr = GroupsController::readGroups();
        json_resp($arr);
    }
}

function handle_group($g_id)
{
    global $method;
    if ($method == "GET") {
        $item = GroupsController::readGroup($g_id);
        if ($item === null) {
            http_response_code(HTTP_BAD_REQUEST);
        } else {
            json_resp($item);
        }
    } else if ($method == "PUT") {
        $data = json_decode(file_get_contents("php://input"));
        if (!GroupsController::updateGroup($g_id, $data)) {
            http_response_code(HTTP_NOT_FOUND);
        }
    } else if ($method == "DELETE") {
        if (GroupsController::deleteGroup($g_id)) {
            http_response_code(HTTP_NO_CONTENT);
        } else {
            http_response_code(HTTP_BAD_REQUEST);
        }
    }
}

function handle_group_tasks($g_id)
{
    global $method;
    if ($method == "POST") {
        $data = json_decode(file_get_contents("php://input"));
        GroupTasksController::createTask($g_id, $data);
        http_response_code(HTTP_CREATED);
    } else if ($method == "GET") {
        $arr = GroupTasksController::readGroupTasks($g_id);
        if ($arr === null) {
            http_response_code(HTTP_BAD_REQUEST);
        } else {
            json_resp($arr);
        }
    }
}

function handle_task($t_id)
{
    global $method;
    if ($method == "GET") {
        $item = TaskController::readTask($t_id);
        if ($item === null) {
            http_response_code(HTTP_BAD_REQUEST);
        } else {
            json_resp($item);
        }
    } else if ($method == "PUT") {
        $data = json_decode(file_get_contents("php://input"));
        if (!TaskController::updateTask($t_id, $data)) {
            http_response_code(HTTP_BAD_REQUEST);
        }
    } else if ($method == "DELETE") {
        if (TaskController::deleteTask($t_id)) {
            http_response_code(HTTP_NO_CONTENT);
        } else {
            http_response_code(HTTP_BAD_REQUEST);
        }
    }
}

