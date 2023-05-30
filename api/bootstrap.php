<?php

use svc\dao\TasksDao;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMSetup;
use svc\dao\ProjectsDao;

require_once __DIR__ . '/DataStore.php';

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/svc/dao/ProjectsDao.php';
require_once __DIR__ . '/svc/dao/TasksDao.php';

class bootstrap
{
    static DataStore $ds;

    static ProjectsDao $projectsDao;

    static TasksDao $tasksDao;

    static MySQLLogger $logger;
}

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
        'path' => __DIR__ . '/todolist.sqlite',
    );
    $em = EntityManager::create($connectionParams, $config);

    bootstrap::$ds = new DataStore($em);

    bootstrap::$projectsDao = new ProjectsDao(bootstrap::$ds);

    bootstrap::$tasksDao = new TasksDao(bootstrap::$ds);

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

    bootstrap::$logger = new MySQLLogger();
    bootstrap::$ds->em()->getConnection()->getConfiguration()->setSQLLogger(bootstrap::$logger);
}

function log_err(Exception $e)
{
    echo $e->getMessage();
}

function logger()
{
    return bootstrap::$logger;
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
    return bootstrap::$ds;
}

function projects_dao(): ProjectsDao
{
    return bootstrap::$projectsDao;
}

function tasks_dao(): TasksDao
{
    return bootstrap::$tasksDao;
}