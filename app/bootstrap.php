<?php
// bootstrap.php
use dao\GroupsDao;
use models\Group;
use models\Task;
use models\TaskEx;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;

require_once('DataStore.php');

require_once(__DIR__ . '/../vendor/autoload.php');

// require_once 'dal/dao/GroupsDao.php';

/**
 * @throws ORMException
 */
function em(): EntityManager
{
    static $entityManager = null;
    if ($entityManager !== null) {
        return $entityManager;
    }
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
    return $entityManager = EntityManager::create($connectionParams, $config);
}

function log_err(Exception $e)
{
    echo $e->getMessage();
}

/**
 * @throws ORMException
 */
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

/**
 * @throws ORMException
 */
function groups()
{
    return em()->getRepository(Group::class);
}

/**
 * @throws ORMException
 */
function find_group($g_id): ?Group
{
    return groups()->find($g_id);
}

/**
 * @throws ORMException
 */
function task_ex()
{
    return em()->getRepository(TaskEx::class);
}

/**
 * @throws ORMException
 */
function find_task_ex($t_id): ?TaskEx
{
    return task_ex()->find($t_id);
}

/**
 * @throws ORMException
 */
function tasks()
{
    return em()->getRepository(Task::class);
}

/**
 * @return Task[]
 * @throws ORMException
 */
function get_group_tasks($g_id): array
{
    return tasks()->findBy(array('g_id' => $g_id), array('t_date' => 'ASC', 't_id' => 'ASC'));
}

/**
 * @throws ORMException
 */
function ds()
{
    static $dataStore = null;
    if ($dataStore !== null) {
        return $dataStore;
    }
    return $dataStore = new DataStore(em()->getConnection());
}

/**
 * @throws ORMException
 */
function groups_dao(): GroupsDao
{
    return new GroupsDao(ds());
}