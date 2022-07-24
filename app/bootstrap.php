<?php
// bootstrap.php
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;

require_once('DataStore.php');

require_once(__DIR__ . '/../vendor/autoload.php');

require_once 'dao/GroupsDao.php';
require_once 'dao/TasksDao.php';

use dao\GroupsDao;
use dao\TasksDao;

/**
 * DataStore
 */
$dataStore = null;

/**
 * GroupsDao
 */
$groupsDao = null;
/**
 * TasksDao
 */
$tasksDao = null;

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
    $em = EntityManager::create($connectionParams, $config);
    global $dataStore;
    $dataStore = new DataStore($em);
    // ....................
    global $groupsDao;
    $groupsDao = new GroupsDao($dataStore);
    // ....................
    global $tasksDao;
    $tasksDao = new TasksDao($dataStore);
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
        ds()->em()->getConnection()->getConfiguration()->setSQLLogger($logger);
    }
    return $logger;
}

/**
 * @throws OptimisticLockException
 * @throws ORMException
 */
function db_flush()
{
    ds()->em()->flush();
}

function ds(): DataStore
{
    global $dataStore;
    return $dataStore;
}

function groups_dao(): GroupsDao
{
    global $groupsDao;
    return $groupsDao;
}

function tasks_dao(): TasksDao
{
    global $tasksDao;
    return $tasksDao;
}