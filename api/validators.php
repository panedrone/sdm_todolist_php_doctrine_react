<?php

function validate_project($data): ?array
{
    if ($data->p_name == null || strlen(trim($data->p_name)) == 0) {
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
            array_push($err, array("priority" => "expected an integer 1..10"));
        }
        if (!validateDate($data->t_date)) {
            array_push($err, array("date" => "invalid date; expected format is 2020-12-31"));
        }
    }
    if (count($err) == 0) {
        return null;
    }
    return $err;
}

function validateDate($date, $format = 'Y-m-d'): bool
{
    $d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
}