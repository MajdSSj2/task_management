<?php
declare(strict_types=1);

use Slim\App;
use App\Config;
use App\Contracts\RequestValidatorFactoryInterface;
use App\Contracts\TaskServiceProviderInterface;
use App\Contracts\UserProviderServiceInterface;
use App\ResponseFormatter;
use App\Services\TaskServiceProvider;
use App\Services\UserProviderService;
use App\Validators\RequestValidatorFactory;

use function DI\create;
use Doctrine\ORM\ORMSetup;
use Slim\Factory\AppFactory;
use Doctrine\ORM\EntityManager;

use Doctrine\DBAL\DriverManager;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

return [
    App::class  => function(ContainerInterface $container){
        AppFactory::setContainer($container);
        $router = require CONFIG_PATH . '/routes/web.php';
        $middleware = require APP_PATH . '/middleware.php';
        $app = AppFactory::create();
        $app->addBodyParsingMiddleware();
        $router($app);
        $middleware($app);
        return $app;
    },

    Config::class => create(Config::class)->constructor(
        require CONFIG_PATH . '/app.php'
    ),
    
    EntityManager::class => function (Config $config) {
        $doctrineSettings = $config->get('doctrine');


        $ormConfig = ORMSetup::createAttributeMetadataConfiguration(
            $doctrineSettings['entity_dir'],
            $doctrineSettings['dev_mode']
        );

        $connection = DriverManager::getConnection(
            $doctrineSettings['connection'], 
            $ormConfig
        );

        return new EntityManager($connection, $ormConfig);
    },

    ResponseFactoryInterface::class =>fn(App $app) => $app->getResponseFactory(),
    ResponseFormatter::class => create(ResponseFormatter::class),    
    //Validator::class => create(Validator::class),
   
    RequestValidatorFactoryInterface::class => fn(ContainerInterface $container) => 
    $container->get(RequestValidatorFactory::class),

    UserProviderServiceInterface::class => fn(ContainerInterface $container) =>
     $container->get(UserProviderService::class),
    
    TaskServiceProviderInterface::class => fn(ContainerInterface $container) =>
    $container->get(TaskServiceProvider::class),
];