<?php
// bootstrap.php
use dal\GroupsTestDao;
use Doctrine\DBAL\Logging\DebugStack;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once('DataStore.php');

require_once('../vendor/autoload.php');

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
    $useSimpleAnnotationReader = false;
    // https://stackoverflow.com/questions/49937252/slim-3-doctrine-2-class-user-does-not-exist-mappingexception
    $paths = array(__DIR__ . '/app/dal/');
    print "paths: " . print_r($paths, true) . PHP_EOL;
    $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);
    // or if you prefer yaml or XML
    // $config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);
    // $config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);

    // https://www.doctrine-project.org/projects/doctrine-orm/en/2.8/reference/basic-mapping.html
    $connectionParams = array(
        'driver' => 'pdo_sqlite',
        'path' => '../todolist.sqlite3',
    );
    return $entityManager = EntityManager::create($connectionParams, $config);
}

function logger() {
    static $logger = null;
    if ($logger === null) {
        $logger = new DebugStack();
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
    return em()->getRepository(dal\Group::class);
}

/**
 * @throws ORMException
 */
function task_ex()
{
    return em()->getRepository(dal\TaskEx::class);
}

/**
 * @throws ORMException
 */
function tasks()
{
    return em()->getRepository(dal\Task::class);
}

/**
 * @throws ORMException
 */
function ds() {
    static $dataStore = null;

    if ($dataStore !== null) {
        return $dataStore;
    }

    return $dataStore = new DataStore(em()->getConnection());
}

function groupsDao(): GroupsTestDao
{
    return new GroupsTestDao(ds());
}