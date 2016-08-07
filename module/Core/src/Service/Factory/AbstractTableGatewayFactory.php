<?php
declare(strict_types=1);

namespace Core\Service\Factory;


use Interop\Container\ContainerInterface;
use Zend\Config\Config;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class AbstractTableGatewayFactory implements AbstractFactoryInterface
{
    public function canCreate(ContainerInterface $container, $requestedName)
    {

        return array_key_exists($requestedName, $container->get('Config')['core_table_gateway']);
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config')['core_table_gateway'][$requestedName];

        $dbAdapter = $container->get(\Zend\Db\Adapter\Adapter::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new $config['entity']);
        $tableGateway = new TableGateway($config['table'], $dbAdapter, null, $resultSetPrototype);

        return new $requestedName($tableGateway);
    }
}