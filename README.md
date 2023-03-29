# sdm_demo_todolist_php_doctrine
Quick Demo of how to use [SQL DAL Maker](https://github.com/panedrone/sqldalmaker) + Doctrine PHP libraries.

Front-end is written in Vue.js, SQLite3 is used as a database.

![demo-go.png](demo-go.png)

dto.xml
```xml
<dto-class name="doctrine-Project" ref="projects"/>

<!--  not-orm Project list item extended with "p_tasks_count":  -->

<dto-class name="ProjectLi" ref="get_projects.sql">
    <field column="p_id" type="int"/>
    <field column="p_name" type="string"/>
    <field column="p_tasks_count" type="int"/>
</dto-class>
        
<!--  all fields are available:  -->

<dto-class name="doctrine-Task" ref="tasks"/>

<!--  "reduced" list item without fetching of "t_comments":   -->

<dto-class name="doctrine-TaskLi" ref="tasks">
    <field column="t_comments" type="-"/>
</dto-class>
```
ProjectsDao.xml
```xml
<crud dto="doctrine-Project"/>

<query-dto-list dto="ProjectLI" method="get_projects"/>
```
TasksDao.xml
```xml
<crud dto="doctrine-Task"/>
```
Generated code in action:
```php
<?php

namespace controllers;

require_once "../bootstrap.php";

require_once '../models/Project.php';

use models\Project;

class ProjectsController
{
    public static function create_project($data)
    {
        $pr = new Project();
        $pr->set_p_name($data->p_name);
        projects_dao()->create_project($pr);
        db_flush();
    }

    public static function read_projects(): array
    {
        $projects = projects_dao()->get_projects();
        if ($projects == null) {
            return array();
        }
        $arr = array();
        foreach ($projects as $pr) {
            $item = array(
                "p_id" => $pr->get_p_id(),
                "p_name" => $pr->get_p_name(),
                "p_tasks_count" => $pr->get_p_tasks_count(),
            );
            array_push($arr, $item);
        }
        return $arr;
    }

    public static function read_project($g_id): array
    {
        $pr = projects_dao()->read_project($g_id);
        if ($pr == null) {
            return array();
        }
        $item = array(
            "p_id" => $pr->get_p_id(),
            "p_name" => $pr->get_p_name(),
        );
        return $item;
    }

    public static function update_project($p_id, $data): bool
    {
        $gr = projects_dao()->read_project($p_id);
        if ($gr == null) {
            return false;
        }
        $gr->set_p_id($p_id);
        $gr->set_p_name($data->p_name);
        projects_dao()->update_project($gr);
        db_flush();
        return true;
    }

    public static function delete_project($p_id)
    {
        projects_dao()->delete_project($p_id);
        db_flush();
    }
}
```