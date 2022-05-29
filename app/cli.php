<?php

use dal\models\Group;

require_once(__DIR__ . '/bootstrap.php');

logger(); // just create it before usage

$gr = new Group();
$gr->set_g_name("Hello from Doctrine " . date("Y-m-d H:i:s"));
em()->persist($gr);
em()->flush();

$g_id = $gr->get_g_id();  // generated id is available!
print_r($g_id . PHP_EOL);

$dao = groups_dao(); // raw-SQL
$groups = $dao->get_groups(); // code-completion is OK
print "Groups: " . print_r($groups, true) . PHP_EOL;

$gr = find_group($g_id);
print "get_g_name: " . $gr->get_g_name() . PHP_EOL; // code-completion is OK
print "Group: " . print_r($gr, true) . PHP_EOL;

$gr = em()->getPartialReference(Group::class, $g_id);
em()->remove($gr);
em()->flush();

$gr_tasks = get_group_tasks(21);
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

// ..... no code-completion :( ...............

$groups = groups()->findAll();
/** @var $gr Group */
foreach ($groups as $gr) {
    echo sprintf("-%s\n", $gr->get_g_name());
}
// https://blog.programster.org/getting-started-with-doctrine-orm
print "Groups: " . print_r($groups, true) . PHP_EOL;

print_r(phpversion());
