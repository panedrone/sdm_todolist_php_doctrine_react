<?php
// bootstrap.php
use dao\GroupsDao;
use dao\TasksDao;
use models\Group;
use models\Task;
use models\TaskLI;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;

require_once('DataStore.php');

require_once(__DIR__ . '/../vendor/autoload.php');

// require_once 'dal/dao/GroupsDao.php';

$em = null;

$dataStore = null;

/**
 * @throws ORMException
 */
function init_app()
{
    // Create a simple "default" Doctrine ORM configuration for Annotations
    $isDevMode = true;
    $proxyDir = null;
    $cache = null;
    // $useSimpleAnnotationReader = false;
    // https://stackoverflow.com/questions/49937252/slim-3-doctrine-2-class-user-does-not-exist-mappingexception
    $paths = array(__DIR__ . '/app/models/');
//    print "paths: " . print_r($paths, true) . PHP_EOL;
    $config = ORMSetup::createAnnotationMetadataConfiguration($paths, $isDevMode, $proxyDir, $cache); //, $useSimpleAnnotationReader;
    // or if you prefer yaml or XML
    // $config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);
    // $config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);

    // https://www.doctrine-project.org/projects/doctrine-orm/en/2.8/reference/basic-mapping.html
    $connectionParams = array(
        'driver' => 'pdo_sqlite',
        'path' => __DIR__ . '/todolist.sqlite3',
    );
    global $em;
    $em = EntityManager::create($connectionParams, $config);
    global $dataStore;
    $dataStore = new DataStore(em()->getConnection());
}

function em(): EntityManager
{
    global $em;
    return $em;
}

function ds()
{
    global $dataStore;
    return $dataStore;
}

function log_err(Exception $e)
{
    echo $e->getMessage();
}

function logger()
{
    static $logger = null;
    if ($logger === null) {
        class MySQLLogger implements Doctrine\DBAL\Logging\SQLLogger
        {
            public function startQuery($sql, ?array $params = null, ?array $types = null)
            {
//                fwrite(STDERR, print_r($sql, true) . PHP_EOL);
//                if ($params != null) {
//                    fwrite(STDERR, sprintf('params: %s', print_r($params, true)));
//                }
            }

            public function stopQuery()
            {
            }
        }

        $logger = new MySQLLogger();
        em()->getConnection()
            ->getConfiguration()
            ->setSQLLogger($logger);
    }
    return $logger;
}

function groups()
{
    return em()->getRepository(Group::class);
}

function find_group($g_id): ?Group
{
    return groups()->find($g_id);
}

function tasks()
{
    return em()->getRepository(Task::class);
}

function find_task($t_id): ?Task
{
    return tasks()->find($t_id);
}

function tasksLI()
{
    return em()->getRepository(TaskLI::class);
}

/**
 * @return Task[]
 */
function get_group_tasks($g_id): array
{
    return tasks()->findBy(array('g_id' => $g_id), array('t_date' => 'ASC', 't_id' => 'ASC'));
}

function groups_dao(): GroupsDao
{
    return new GroupsDao(ds());
}

function tasks_dao(): TasksDao
{
    return new TasksDao(ds());
}