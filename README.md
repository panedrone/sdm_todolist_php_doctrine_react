# sdm_demo_todolist_php_doctrine
Quick Demo of how to use [SQL DAL Maker](https://github.com/panedrone/sqldalmaker) + Doctrine PHP libraries.

Front-end is written in Vue.js, SQLite3 is used as database.

![demo-go.png](demo-go.png)

![erd.png](erd.png)

```xml
<dto-classes>
    
    <dto-class name="doctrine-Group" ref="groups"/>

    <!--    list item extended with "g_tasks_count":   -->

    <dto-class name="GroupLI" ref="get_groups.sql">
        <field column="g_id" type="int"/>
        <field column="g_name" type="string"/>
        <field column="g_tasks_count" type="int"/>
    </dto-class>

    <!--    all fields are available: -->

    <dto-class name="doctrine-Task" ref="tasks"/>

    <!--    "reduced" list item without fetching of "t_comments":   -->

    <dto-class name="doctrine-TaskLI" ref="tasks">
        <field column="t_comments" type="-"/>
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
```