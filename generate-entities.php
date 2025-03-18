<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Symfony\Component\Dotenv\Dotenv;
use App\Doctrine\EnumType;

require 'vendor/autoload.php';

// Charger les variables d’environnement
$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/.env');

// Enregistrer le type ENUM
if (!Type::hasType('enum')) {
    Type::addType('enum', EnumType::class);
}

// Configuration de la connexion
$connectionParams = [
    'dbname' => 'oboba',
    'user' => 'jp',
    'password' => 'D5tgcu8vhu,',
    'host' => 'localhost',
    'port' => 3306,
    'driver' => 'pdo_mysql',
    'charset' => 'utf8mb4',
    'serverVersion' => 'mariadb-10.6.18',
];

$conn = DriverManager::getConnection($connectionParams);
$conn->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'enum');

// Configuration Doctrine ORM
$config = Setup::createAnnotationMetadataConfiguration([__DIR__ . '/src/Entity'], true);
$config->setProxyDir(__DIR__ . '/var/cache/proxies');
$config->setProxyNamespace('App\Proxies');
$config->setAutoGenerateProxyClasses(true);

// Créer l'EntityManager avec la méthode statique
$entityManager = EntityManager::create($conn, $config);

// Générer les entités
$schemaManager = $conn->getSchemaManager();
$tables = $schemaManager->listTables();
$metadata = [];

foreach ($tables as $table) {
    $className = 'App\\Entity\\' . ucfirst(str_replace('_', '', $table->getName()));
    $classMetadata = new \Doctrine\ORM\Mapping\ClassMetadata($className);
    $classMetadata->setTableName($table->getName());
    
    foreach ($table->getColumns() as $column) {
        $type = $column->getType()->getName();
        $fieldMapping = [
            'fieldName' => $column->getName(),
            'columnName' => $column->getName(),
            'type' => $type,
            'nullable' => !$column->getNotnull(),
        ];
        if ($column->getAutoincrement()) {
            $classMetadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_AUTO);
            $fieldMapping['id'] = true;
            $fieldMapping['generatedValue'] = true; // Correction pour Doctrine 2.9.5
        }
        $classMetadata->mapField($fieldMapping);
    }
    $metadata[] = $classMetadata;
}

$generator = new \Doctrine\ORM\Tools\EntityGenerator();
$generator->setGenerateAnnotations(true);
$generator->setGenerateStubMethods(true);
$generator->setRegenerateEntityIfExists(true);
$generator->setUpdateEntityIfExists(false);
$generator->generate($metadata, __DIR__ . '/src/Entity');

echo "Entités générées avec succès dans src/Entity !\n";
