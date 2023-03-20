<?php

function validate_group($data): ?array
{
    if ($data->g_name == null || strlen(trim($data->g_name)) == 0) {
        return array("group" => "name not set");
    }
    return null;
}

function validate_task($data, $existing) : ?array
{
    $err = array();
    if ($data->t_subject == null || strlen(trim($data->t_subject)) == 0) {
        array_push($err, array("subject" => "not set"));
    }
    if ($existing) {
        if (!is_int($data->t_priority) || $data->t_priority < 1 || $data->t_priority > 10) {
            array_push($err, array("priority" => "need an integer 1..10"));
        }
    }
    if (count($err) == 0) {
        return null;
    }
    return $err;
}

