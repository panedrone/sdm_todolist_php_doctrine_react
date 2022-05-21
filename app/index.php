<?php

require_once(__DIR__ . '/bootstrap.php');

logger(); // just create it before usage

$gr = new dal\Group();
$gr->set_g_name("Hello from Doctrine " . date("Y-m-d H:i:s"));
em()->persist($gr);
em()->flush();

$g_id = $gr->get_g_id();  // generated id is available!
print_r($g_id . PHP_EOL);

if ($g_id == null){
    print "null\n";
}

$groups = groups()->findAll();
/** @var $gr dal\Group */
foreach ($groups as $gr) {
    echo sprintf("-%s\n", $gr->get_g_name());
}
// https://blog.programster.org/getting-started-with-doctrine-orm
print "Groups: " . print_r($groups, true) . PHP_EOL;

$gr = $groups = groups()->find($g_id);
print "Group: " . print_r($gr, true) . PHP_EOL;

print dal\Group::class;
$entity = em()->getPartialReference(dal\Group::class, $g_id);
em()->remove($entity);
em()->flush();

$dao = groupsDao(); // raw-SQL
$groups = $dao->get_groups();
print "Groups: " . print_r($groups, true) . PHP_EOL;

$rows_affected = $dao->rename_group("Hello from Doctrine " . date("Y-m-d H:i:s"), 66);
print "rows_affected: " . print_r($rows_affected, true) . PHP_EOL;

$gr = $dao->get_group(66);
print "Group: " . print_r($gr, true) . PHP_EOL;

$group_ids = $dao->get_groups_ids(); // raw-SQL
print "group_ids: " . print_r($group_ids, true) . PHP_EOL;

$g_id = $dao->get_group_id(21);
print "g_id: " . print_r($g_id, true) . PHP_EOL;

// https://stackoverflow.com/questions/12048452/how-to-order-results-with-findby-in-doctrine
// The second parameter of findBy is for ORDER.
$group_tasks = tasks()->findBy(array('g_id' => 21), array('t_date' => 'ASC', 't_id' => 'ASC'));
print "group_tasks: " . print_r($group_tasks, true) . PHP_EOL;

print "logger()->currentQuery: " . logger()->currentQuery . PHP_EOL;
$current = logger()->queries[logger()->currentQuery];
echo sprintf(
    '%s%sparams: [%s]',
    $current['sql'], PHP_EOL,
    implode($current['params'])
);

