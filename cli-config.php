<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use App\Config;
use Doctrine\ORM\EntityManager;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;

// 1. Get the container
$container = require 'bootstrap.php';

// 2. Get the EntityManager from the container
// This already contains the Connection and the Entity mapping
$entityManager = $container->get(EntityManager::class);

// 3. Load migrations configuration
$config = new PhpFile(__DIR__ . '/migrations.php'); 

// 4. Return the DependencyFactory using the REAL EntityManager
return DependencyFactory::fromEntityManager(
    $config, 
    new ExistingEntityManager($entityManager)
);