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
    
    const TABLE_PUPILS = 'sas-cc_pupils';
    const TABLE_CLASSES = 'sas-cc_classes';
    const TABLE_COMPANIES = 'sas-cc_companies';
    
    public function __construct(Connection $c) {
        $this->connection = $c;      
    }
    
    public function createTables() {
        $sql = $this->getMigrateStatements();
        foreach ($sql as $statement) {
            $this->connection->executeQuery($statement);
        }
    }
    
    protected function getMigrateStatements() {
        $schema = static::getSchema($this->getChannelTableName(), $this->getCrawlDateTableName());
        $currentSchema = clone $this->connection->getSchemaManager()->createSchema();
        $diff = \Doctrine\DBAL\Schema\Comparator::compareSchemas($currentSchema, $schema);
        return $diff->toSaveSql($this->connection->getDatabasePlatform());
    }
        
    public static function getSchema()
    {
        $schema = new Schema();
        
        $classesTable = $this->generateClassesTable($schema);
        $companiesTable = $this->generateCompanyTable($schema);
        $this->generatePupilsTable($schema, $classesTable, $companiesTable);
        
        return $schema;
    }
    
    protected function createClassesTable(Schema $schema) {
        $classesTable = $schema->createTable(self::TABLE_CLASSES);
        $classesTable->addColumn('id', 'integer')->setAutoincrement(true);
        $classesTable->addColumn('identifier', 'string');
        $classesTable->addColumn('grade', 'string');
        $classesTable->setPrimaryKey(array('id'));
        $classesTable->setPrimaryKey(array('id'));
        return $classesTable;
    }
    
    protected function createCompaniesTable(Schema $schema) {
        $companiesTable = $schema->createTable(self::TABLE_COMPANIES);
        $companiesTable->addColumn('id', 'integer')->setAutoincrement(true);
        $companiesTable->addColumn('needs', 'text');
        $companiesTable->addColumn('description', 'text');
        $companiesTable->addColumn('requires_manual_assignment', 'boolean');
        $companiesTable->addColumn('special_rules', 'text');
        $companiesTable->addColumn('max_workspaces', 'int');
        $companiesTable->addColumn('min_workspaces', 'int');
        $companiesTable->addColumn('min_grade', 'string');
        $companiesTable->addColumn('max_grade', 'string');
        
        $companiesTable->setPrimaryKey(array('id'));
        return $companiesTable;
    }
    
    protected function createPupilsTable(Schema $schema, $classesTable, $companyTable) {
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
