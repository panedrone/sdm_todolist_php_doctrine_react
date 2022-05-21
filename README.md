# sdm_demo_todolist_php_doctrine
Quick Demo of how to use [SQL DAL Maker](https://github.com/panedrone/sqldalmaker) + PHP + Doctrine models + DTO and DAO classes for Doctrine raw-SQL.
```xml
<dto-classes>
    <dto-class name="GroupEx" ref="get_groups.sql"/>
    <dto-class name="doctrine-Group" ref="groups"/>
    <dto-class name="doctrine-Task" ref="tasks"/>
</dto-classes>
```
```xml
<dao-class>
    <query-dto-list method="getGroups" ref="get_groups.sql" dto="GroupEx"/>
</dao-class>
```
```php
$gr = new dal\Group();
$gr->setGName("Hello from Doctrine " . date("Y-m-d H:i:s"));
em()->persist($gr);
em()->flush();

$g_id = $gr->getGId(); // new id is available now

$gr = $groups = groupsRepo()->find($g_id);
print "Group: " . print_r($gr, true) . PHP_EOL;

$entity = em()->getPartialReference(dal\Group::class, $g_id);
em()->remove($entity);
em()->flush();
    
$groups = groupsDao()->get_groups(); // raw-SQL
print "Groups: " . print_r($groups, true) . PHP_EOL;
```