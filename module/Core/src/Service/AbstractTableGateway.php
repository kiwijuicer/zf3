<?php
declare(strict_types=1);

namespace Core\Service;


use Zend\Config\Config;
use Zend\Db\ResultSet\ResultSet;

class AbstractTableGateway
{
    public function __invoke($serviceManager)
    {
        $config = new Config($serviceManager->get('Config'));

        $tableGateway = $serviceManager->get(\Core\Service\Factory\AbstractTableGateway::class);
        $table = new AlbumTable($tableGateway);
        return $table;
    }
}