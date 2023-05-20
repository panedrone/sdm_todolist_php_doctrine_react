<?php

namespace api;

// https://github.com/symfony/http-foundation/blob/5.4/Response.php

const HTTP_CREATED = 201;
const HTTP_NO_CONTENT = 204;

const HTTP_BAD_REQUEST = 400;
const HTTP_NOT_FOUND = 404;
const HTTP_METHOD_NOT_ALLOWED = 405;

const HTTP_INTERNAL_SERVER_ERROR = 500;

function json_resp($data)
{
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($data);
}

function get_request_method()
{
    return filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_SPECIAL_CHARS);
}
