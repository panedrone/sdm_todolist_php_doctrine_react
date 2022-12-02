<?php

use models\Group;

require_once(__DIR__ . '/bootstrap.php');

try {
    logger(); // just create it before usage

    $dao = groups_dao();

    $gr = new Group();
    $gr->set_g_name("Hello from Doctrine " . date("Y-m-d H:i:s"));
    $dao->create_group($gr);

    $g_id = $gr->get_g_id();  // generated id is available!
    print_r($g_id . PHP_EOL);

    $groups = $dao->get_all_groups(); // code-completion is OK
    print "Groups: " . print_r($groups, true) . PHP_EOL;

    $gr = $dao->read_group($g_id);
    print "get_g_name: " . $gr->get_g_name() . PHP_EOL; // code-completion is OK
    print "Group: " . print_r($gr, true) . PHP_EOL;

    $dao->delete_group($g_id);

    $gr_tasks = tasks_dao()->get_group_tasks(21);
    print "get_t_subject: " . $gr_tasks[0]->get_t_subject() . PHP_EOL; // code-completion is OK
    print "group_tasks: " . print_r($gr_tasks, true) . PHP_EOL;

// ....................

    $rows_affected = $dao->rename_group("Hello from Doctrine " . date("Y-m-d H:i:s"), 66);
    print "rows_affected: " . print_r($rows_affected, true) . PHP_EOL;

    $gr = $dao->get_group(66);
    print "Group: " . print_r($gr, true) . PHP_EOL;

    $group_ids = $dao->get_groups_ids(); // raw-SQL
    print "group_ids: " . print_r($group_ids, true) . PHP_EOL;

    $g_id = $dao->get_group_id(21);
    print "g_id: " . print_r($g_id, true) . PHP_EOL;

// ....................

    print_r(phpversion());

} catch (\Exception $e) {
    print_r($e);
}
