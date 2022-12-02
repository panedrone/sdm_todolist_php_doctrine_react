# sdm_demo_todolist_php_doctrine
Quick Demo of how to use [SQL DAL Maker](https://github.com/panedrone/sqldalmaker) + Doctrine PHP libraries

![demo-go.png](demo-go.png)

![erd.png](erd.png)

```xml
<dto-classes>
    
    <dto-class name="doctrine-Group" ref="groups"/>

    <!--    list item extended with "tasks_count":   -->

    <dto-class name="GroupLI" ref="get_groups.sql">
        <field column="g_id" type="int"/>
        <field column="g_name" type="string"/>
        <field column="tasks_count" type="int"/>
    </dto-class>

    <!--    all fields are available: -->

    <dto-class name="doctrine-Task" ref="tasks"/>

    <!--    "reduced" list item without fetching of "t_comments":   -->

    <dto-class name="doctrine-TaskLI" ref="tasks">
        <field column="t_comments" type="string"/>
    </dto-class>
    
</dto-classes>
```
```xml
<dao-class>
    <crud dto="doctrine-Group" table="groups"/>
    <query-dto-list dto="GroupLI" method="get_all_groups"/>
</dao-class>
```
```xml
<dao-class>
    <crud dto="doctrine-Task" table="tasks"/>
</dao-class>
```
```php
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
```