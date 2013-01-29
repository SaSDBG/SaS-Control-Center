<?php

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;

/**
 * Description of SchemaManager
 *
 * @author drak3
 */
class SchemaManager {
    
    /**
     *
     * @var Connection
     */
    protected $connection;
    
    public function __construct(Connection $c, $prefix) {
        $this->connection = $c;
        $this->prefix = $prefix;
        
    }
    
    
}

?>
