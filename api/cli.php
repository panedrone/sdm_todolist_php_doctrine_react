<?php

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/svc/models/Project.php';
use svc\models\Project;

try {
    logger(); // just create it before usage

    $dao = projects_dao();

    $gr = new Project();
    $gr->set_p_name("Hello Doctrine " . date("Y-m-d H:i:s"));
    $dao->create_project($gr);

    $g_id = $gr->get_p_id();  // generated id is available!
    print_r($g_id . PHP_EOL);

    $projects = $dao->get_projects(); // code-completion is OK
    print "projects: " . print_r($projects, true) . PHP_EOL;

    $gr = $dao->read_project($g_id);
    print "get_g_name: " . $gr->get_p_name() . PHP_EOL; // code-completion is OK
    print "project: " . print_r($gr, true) . PHP_EOL;

    $dao->delete_project($g_id);

    $gr_tasks = tasks_dao()->get_project_tasks(21);
    print "get_t_subject: " . $gr_tasks[0]->get_t_subject() . PHP_EOL; // code-completion is OK
    print "project_tasks: " . print_r($gr_tasks, true) . PHP_EOL;

// ....................

    $rows_affected = $dao->rename_project("Hello Doctrine " . date("Y-m-d H:i:s"), 66);
    print "rows_affected: " . print_r($rows_affected, true) . PHP_EOL;

    $gr = $dao->read_project(66);
    print "project: " . print_r($gr, true) . PHP_EOL;

// ....................

    print_r(phpversion());

} catch (Exception $e) {
    print_r($e);
}
