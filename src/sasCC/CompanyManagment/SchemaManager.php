<?php
namespace sasCC\CompanyManagment;
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
    
    const TABLE_PUPILS = 'sascc_pupils';
    const TABLE_CLASSES = 'sascc_classes';
    const TABLE_COMPANIES = 'sascc_companies';
    
    public function __construct(Connection $c) {
        $this->connection = $c;      
    }
    
    public function createTables() {
        $sql = $this->getMigrateStatements();
        foreach ($sql as $statement) {
            print_r($statement);
            $this->connection->executeQuery($statement);
        }
    }
    
    protected function getMigrateStatements() {
        $schema = static::getSchema();
        $currentSchema = clone $this->connection->getSchemaManager()->createSchema();
        $diff = \Doctrine\DBAL\Schema\Comparator::compareSchemas($currentSchema, $schema);
        return $diff->toSaveSql($this->connection->getDatabasePlatform());
    }
        
    public static function getSchema()
    {
        $schema = new Schema();
        
        $classesTable = self::createClassesTable($schema);
        $companiesTable = self::createCompaniesTable($schema);
        self::createPupilsTable($schema, $classesTable, $companiesTable);
        
        return $schema;
    }
    
    protected static function createClassesTable(Schema $schema) {
        $classesTable = $schema->createTable(self::TABLE_CLASSES);
        $classesTable->addColumn('id', 'integer')->setAutoincrement(true);
        $classesTable->addColumn('identifier', 'string');
        $classesTable->addColumn('grade', 'string');
        $classesTable->setPrimaryKey(array('id'));
        return $classesTable;
    }
    
    protected static function createCompaniesTable(Schema $schema) {
        $companiesTable = $schema->createTable(self::TABLE_COMPANIES);
        $companiesTable->addColumn('id', 'integer')->setAutoincrement(true);
        $companiesTable->addColumn('needs', 'text');
        $companiesTable->addColumn('description', 'text');
        $companiesTable->addColumn('requires_manual_assignment', 'boolean');
        $companiesTable->addColumn('special_rules', 'text');
        $companiesTable->addColumn('max_workspaces', 'integer');
        $companiesTable->addColumn('min_workspaces', 'integer');
        $companiesTable->addColumn('min_grade', 'string');
        $companiesTable->addColumn('max_grade', 'string');
        
        $companiesTable->setPrimaryKey(array('id'));
        return $companiesTable;
    }
    
    protected static function createPupilsTable(Schema $schema, $classesTable, $companyTable) {
        $pupilsTable = $schema->createTable(self::TABLE_PUPILS);
        $pupilsTable->addColumn('id', 'integer')->setAutoincrement(true);
        $pupilsTable->addColumn('name', 'string');
        $pupilsTable->addColumn('class_id', 'integer');
        $pupilsTable->addForeignKeyConstraint($classesTable, array('class_id'), array('id'), array(
            'onUpdate' => 'CASCADE',
            'onDelete' => 'CASCADE',
        ));
        $pupilsTable->addColumn('company_id', 'integer');
        $pupilsTable->addForeignKeyConstraint($companyTable, array('company_id'), array('id'), array(
            'onUpdate' => 'CASCADE',
            'onDelete' => 'CASCADE',
        ));
        $pupilsTable->addColumn('role', 'string');
        
        $pupilsTable->setPrimaryKey(array('id'));
        return $pupilsTable;
    }
}

?>
