# sdm_demo_todolist_php_doctrine

Quick Demo of how to use [SQL DAL Maker](https://github.com/panedrone/sqldalmaker) + Doctrine PHP libraries.

Front-end is written in Vue.js, SQLite3 is used as a database.

![demo-go.png](demo-go.png)

sdm.xml

```xml

<sdm>

    <dto-class name="doctrine-Project" ref="projects"/>

    <dto-class name="ProjectLi" ref="projects">

        <field column="p_tasks_count" type="int"/>

    </dto-class>

    <dto-class name="doctrine-Task" ref="tasks"/>

    <dto-class name="doctrine-TaskLi" ref="tasks">

        <field column="t_comments" type="-"/>

    </dto-class>

    <dao-class name="ProjectsDao">

        <crud dto="doctrine-Project"/>

        <query-dto-list dto="ProjectLi" method="get_projects" ref="get_projects.sql"/>

    </dao-class>

    <dao-class name="TasksDaoGenerated">

        <crud dto="doctrine-Task"/>

    </dao-class>

</sdm>
```

Generated code in action:

```php
<?php

require_once __DIR__ . "/../bootstrap.php";

require_once __DIR__ . '/models/Project.php';

use svc\models\Project;

function project_create($data)
{
    $pr = new Project();
    $pr->set_p_name($data->p_name);
    projects_dao()->create_project($pr);
    db_flush();
}

function projects_read_all(): array
{
    $projects = projects_dao()->get_projects();
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

function project_read($g_id): array
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

function project_update($p_id, $data): bool
{
    $pr = projects_dao()->read_project($p_id);
    if ($pr == null) {
        return false;
    }
    $pr->set_p_id($p_id);
    $pr->set_p_name($data->p_name);
    projects_dao()->update_project($pr);
    db_flush();
    return true;
}

function project_delete($p_id)
{
    projects_dao()->delete_project($p_id);
    db_flush();
}
```