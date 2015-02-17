<?php
namespace Mekit\Bundle\RelationshipBundle\Migration\Extension;

use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;

use Oro\Bundle\EntityConfigBundle\Config\ConfigModelManager;
use Oro\Bundle\EntityExtendBundle\EntityConfig\ExtendScope;
use Oro\Bundle\EntityExtendBundle\Migration\EntityMetadataHelper;
use Oro\Bundle\EntityExtendBundle\Migration\ExtendOptionsManager;
use Oro\Bundle\EntityExtendBundle\Migration\OroOptions;
use Oro\Bundle\EntityExtendBundle\Extend\RelationType;
use Oro\Bundle\EntityExtendBundle\Tools\ExtendDbIdentifierNameGenerator;
use Oro\Bundle\EntityExtendBundle\Tools\ExtendHelper;
use Oro\Bundle\MigrationBundle\Tools\DbIdentifierNameGenerator;
use Oro\Bundle\MigrationBundle\Migration\Extension\NameGeneratorAwareInterface;

//use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtension;
/**
 * This class is a 'copy' of Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtension where we are missing the
 * addOneToOneRelation method - whilst waiting for this method to be implemented we are using this class to generate
 * OneToOne relationships needed for the relationship bundle
 *
 * Class ExtendExtension
 */
class ExtendExtension implements NameGeneratorAwareInterface {

	const ONE2ONE_COLUMN_NAME_PREFIX= 're';//@todo: clear this up when finished and remove it from generateOneToOneRelationColumnName

	const AUTO_GENERATED_ID_COLUMN_NAME = 'id';

	/**
	 * @var ExtendOptionsManager
	 */
	protected $extendOptionsManager;

	/**
	 * @var EntityMetadataHelper
	 */
	protected $entityMetadataHelper;

	/**
	 * @var ExtendDbIdentifierNameGenerator
	 */
	protected $nameGenerator;

	/**
	 * @param ExtendOptionsManager $extendOptionsManager
	 * @param EntityMetadataHelper $entityMetadataHelper
	 */
	public function __construct(
		ExtendOptionsManager $extendOptionsManager,
		EntityMetadataHelper $entityMetadataHelper
	) {
		$this->extendOptionsManager = $extendOptionsManager;
		$this->entityMetadataHelper = $entityMetadataHelper;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setNameGenerator(DbIdentifierNameGenerator $nameGenerator) {
		$this->nameGenerator = $nameGenerator;
	}


	/**
	 * Adds one-to-one relation
	 *
	 * @param Schema       $schema
	 * @param Table|string $table A Table object or table name
	 * @param string       $associationName A relation name
	 * @param Table|string $targetTable A Table object or table name
	 * @param string[]     $targetTitleColumnNames Column names are used to show a title of related entity
	 * @param string[]     $targetDetailedColumnNames Column names are used to show detailed info about related entity
	 * @param string[]     $targetGridColumnNames Column names are used to show related entity in a grid
	 * @param array        $options Entity config values
	 *                                                format is [CONFIG_SCOPE => [CONFIG_KEY => CONFIG_VALUE]]
	 * @param string       $fieldType The field type. By default the field type is oneToMany,
	 *                                                but you can specify another type if it is based on oneToOne.
	 *                                                In this case this type should be registered
	 *                                                in entity_extend.yml under underlying_types section
	 */
	public function addOneToOneRelation(
		Schema $schema,
		$table,
		$associationName,
		$targetTable,
		array $targetTitleColumnNames,
		array $targetDetailedColumnNames,
		array $targetGridColumnNames,
		array $options = [],
		$fieldType = RelationType::ONE_TO_ONE
	) {
		$this->ensureExtendFieldSet($options);

		$selfTableName = $this->getTableName($table);
		$selfTable = $this->getTable($table, $schema);
		$selfClassName = $this->getEntityClassByTableName($selfTableName);
		$selfPrimaryKeyColumnName = $this->getPrimaryKeyColumnName($selfTable);
		$selfPrimaryKeyColumn = $selfTable->getColumn($selfPrimaryKeyColumnName);

		$targetTableName = $this->getTableName($targetTable);
		$targetTable = $this->getTable($targetTable, $schema);
		$targetColumnName = $this->generateOneToOneRelationColumnName($associationName);
		$targetPrimaryKeyColumnName = $this->getPrimaryKeyColumnName($targetTable);

		$this->checkColumnsExist($targetTable, $targetTitleColumnNames);
		$this->checkColumnsExist($targetTable, $targetDetailedColumnNames);
		$this->checkColumnsExist($targetTable, $targetGridColumnNames);



		echo "\nassociationName: " . $associationName . "\n";
		echo "\ntargetColumnName: " . $targetColumnName . "\n";


		$this->addRelationColumn($targetTable, $targetColumnName, $selfPrimaryKeyColumn, ['notnull' => true]);
		$targetTable->addUniqueIndex([$targetColumnName]);
		$targetTable->addForeignKeyConstraint($selfTable, [$targetColumnName], [$selfPrimaryKeyColumnName], ['onDelete' => 'RESTRICT']);


		/*

		$options[ExtendOptionsManager::TARGET_OPTION] = [
			'table_name' => $targetTableName,
			'columns'    => [
				'title'    => $targetTitleColumnNames,
				'detailed' => $targetDetailedColumnNames,
				'grid'     => $targetGridColumnNames,
			],
		];

		$options[ExtendOptionsManager::TYPE_OPTION] = $fieldType;
		$this->extendOptionsManager->setColumnOptions(
			$selfTableName,
			$associationName,
			$options
		);
		*/
	}


	/**
	 * Builds a column name for a one-to-many relation
	 *
	 * @param string $associationName
	 * @return string
	 */
	public function generateOneToOneRelationColumnName($associationName)
	{
		return sprintf(
			'%s_%s_%s',
			self::ONE2ONE_COLUMN_NAME_PREFIX,
			$associationName,
			"id"
		);
	}

	/**
	 * @param Table  $table
	 * @param string $columnName
	 * @param Column $targetColumn
	 * @param array  $options
	 * @throws \Doctrine\DBAL\Schema\SchemaException
	 */
	protected function addRelationColumn(Table $table, $columnName, Column $targetColumn, array $options = []) {
		if ($targetColumn->getName() !== 'id') {
			throw new SchemaException(
				sprintf(
					'The target column name must be "id". Relation column: "%s::%s". Target column name: "%s".',
					$table->getName(),
					$columnName,
					$targetColumn->getName()
				)
			);
		}
		$columnTypeName = $targetColumn->getType()->getName();
		if (!in_array($columnTypeName, [Type::INTEGER, Type::STRING, Type::SMALLINT, Type::BIGINT])) {
			throw new SchemaException(
				sprintf(
					'The type of relation column "%s::%s" must be an integer or string. "%s" type is not supported.',
					$table->getName(),
					$columnName,
					$columnTypeName
				)
			);
		}

		if ($columnTypeName === Type::STRING && $targetColumn->getLength() !== null) {
			$options['length'] = $targetColumn->getLength();
		}

		$table->addColumn($columnName, $columnTypeName, $options);
	}

	/**
	 * @param Table    $table
	 * @param string[] $columnNames
	 * @throws \InvalidArgumentException if $columnNames array is empty
	 * @throws SchemaException if any column is not exist
	 */
	protected function checkColumnsExist($table, array $columnNames) {
		if (empty($columnNames)) {
			throw new \InvalidArgumentException('At least one column must be specified.');
		}
		foreach ($columnNames as $columnName) {
			$table->getColumn($columnName);
		}
	}

	/**
	 * @param Table $table
	 * @return string
	 * @throws SchemaException if valid primary key does not exist
	 */
	protected function getPrimaryKeyColumnName(Table $table) {
		if (!$table->hasPrimaryKey()) {
			throw new SchemaException(
				sprintf('The table "%s" must have a primary key.', $table->getName())
			);
		}
		$primaryKeyColumns = $table->getPrimaryKey()->getColumns();
		if (count($primaryKeyColumns) !== 1) {
			throw new SchemaException(
				sprintf('A primary key of "%s" table must include only one column.', $table->getName())
			);
		}

		return array_pop($primaryKeyColumns);
	}


	/**
	 * @param Table|string $table A Table object or table name
	 * @return string
	 */
	protected function getTableName($table) {
		return $table instanceof Table ? $table->getName() : $table;
	}

	/**
	 * @param Table|string $table A Table object or table name
	 * @param Schema       $schema
	 * @return Table
	 */
	protected function getTable($table, Schema $schema) {
		return $table instanceof Table ? $table : $schema->getTable($table);
	}


	/**
	 * Gets an entity full class name by a table name
	 *
	 * @param string $tableName
	 * @return string|null
	 */
	public function getEntityClassByTableName($tableName) {
		return $this->entityMetadataHelper->getEntityClassByTableName($tableName);
	}

	/**
	 * Makes sure that required for any extend field attributes are set
	 *
	 * @param array $options
	 */
	protected function ensureExtendFieldSet(array &$options) {
		if (!isset($options['extend'])) {
			$options['extend'] = [];
		}
		if (!isset($options['extend']['is_extend'])) {
			$options['extend']['is_extend'] = true;
		}
		if (!isset($options['extend']['owner'])) {
			$options['extend']['owner'] = ExtendScope::OWNER_SYSTEM;
		}
	}

}