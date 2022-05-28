# sdm_demo_todolist_php_doctrine
Quick Demo of how to use [SQL DAL Maker](https://github.com/panedrone/sqldalmaker) + PHP + Doctrine models + DTO and DAO classes for Doctrine raw-SQL.

![demo-go.png](demo-go.png)

![erd.png](erd.png)

```xml
<dto-classes>
    <dto-class name="GroupEx" ref="get_groups.sql"/>
    <dto-class name="doctrine-Group" ref="groups"/>
    <dto-class name="doctrine-Task" ref="tasks"/>
</dto-classes>
```
```xml
<dao-class>
    <query-dto-list method="get_groups" dto="GroupEx"/>
</dao-class>
```
```php
$gr = new Group();
$gr->set_g_name("Hello from Doctrine " . date("Y-m-d H:i:s"));
em()->persist($gr);
em()->flush();

$g_id = $gr->get_g_id(); // generated id is available!

$gr = $groups = groups()->find($g_id);
print "Group: " . print_r($gr, true) . PHP_EOL;

$entity = em()->getPartialReference(Group::class, $g_id);
em()->remove($entity);
em()->flush();
    
$groups = groups_dao()->get_groups(); // raw-SQL
print "Groups: " . print_r($groups, true) . PHP_EOL;

$group_tasks = tasks()->findBy(array('g_id' => 21), array('t_date' => 'ASC', 't_id' => 'ASC'));
print "group_tasks: " . print_r($group_tasks, true) . PHP_EOL;
```