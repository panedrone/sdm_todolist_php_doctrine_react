# sdm_demo_todolist_php_doctrine
Quick Demo of how to use [SQL DAL Maker](https://github.com/panedrone/sqldalmaker) + Doctrine PHP libraries.

Front-end is written in Vue.js, SQLite3 is used as database.

![demo-go.png](demo-go.png)

![erd.png](erd.png)

dto.xml
```xml
<dto-class name="doctrine-Project" ref="projects"/>

<!--  not-orm list item extended with "p_tasks_count":  -->

<dto-class name="ProjectLI" ref="get_projects.sql">
    <field column="p_id" type="int"/>
    <field column="p_name" type="string"/>
    <field column="p_tasks_count" type="int"/>
</dto-class>

<!--  all fields are available:  -->

<dto-class name="doctrine-Task" ref="tasks"/>

<!--  "reduced" list item without fetching of "t_comments":   -->

<dto-class name="doctrine-TaskLI" ref="tasks">
    <field column="t_comments" type="-"/>
</dto-class>
```
ProjectsDao.xml
```xml
<crud dto="doctrine-Project" table="projects"/>
<query-dto-list dto="ProjectLI" method="get_all_projects"/>
```
TasksDao.xml
```xml
<crud table="tasks" dto="Task"/>
<query-dto-list method="GetProjectTasks(int64 gId)" ref="get_project_tasks.sql" dto="TaskLi"/>
```
Generated code in action:
```php
$dao = projects_dao();

$gr = new Project();
$gr->set_p_name("Hello from Doctrine " . date("Y-m-d H:i:s"));
$dao->create_project($gr);

$p_id = $gr->get_p_id();  // generated id is available!
print_r($p_id . PHP_EOL);

$projects = $dao->get_all_projects(); // code-completion is OK
print "Projects: " . print_r($projects, true) . PHP_EOL;

$gr = $dao->read_project($p_id);
print "get_p_name: " . $gr->get_p_name() . PHP_EOL; // code-completion is OK
print "Project: " . print_r($gr, true) . PHP_EOL;

$dao->delete_project($p_id);

$gr_tasks = tasks_dao()->get_project_tasks(21);
print "get_t_subject: " . $gr_tasks[0]->get_t_subject() . PHP_EOL; // code-completion is OK
print "project_tasks: " . print_r($gr_tasks, true) . PHP_EOL;
```