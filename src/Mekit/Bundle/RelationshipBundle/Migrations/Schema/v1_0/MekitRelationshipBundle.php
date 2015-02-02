<?php
namespace Mekit\Bundle\RelationshipBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class MekitRelationshipBundle implements Migration {
	public static $tableNameReferenceable = "mekit_referenceable";
	public static $tableNameReferences = "mekit_references";

	/**
	 * @param Schema   $schema
	 * @param QueryBag $queries
	 */
	public function up(Schema $schema, QueryBag $queries) {
		$this->createMekitReferenceableTable($schema);
		$this->createMekitReferencesTable($schema);
	}

	/**
	 * Create mekit_referenceable table
	 *
	 * @todo: for now we are hardcoding FKs - instead we should be using  OroEntityExtendBundle\Migration\Extension\ExtendExtension
	 *      but we need addOneToOneRelation method which is missing for now
	 *
	 * @param Schema $schema
	 */
	protected function createMekitReferenceableTable(Schema $schema) {
		$table = $schema->createTable(self::$tableNameReferenceable);
		$table->addColumn('id', 'integer', ['autoincrement' => true]);

		//INDEXES
		$table->setPrimaryKey(['id']);

		//EXTERNAL REFERENCES
		$this->createReferenceForTable('mekit_account', 'account_id', $table, $schema);
		$this->createReferenceForTable('mekit_contact', 'contact_id', $table, $schema);
		$this->createReferenceForTable('mekit_call', 'call_id', $table, $schema);
		$this->createReferenceForTable('mekit_meeting', 'meeting_id', $table, $schema);
		$this->createReferenceForTable('mekit_task', 'task_id', $table, $schema);
	}

	/**
	 * Create mekit_references table
	 *
	 * @param Schema $schema
	 */
	protected function createMekitReferencesTable(Schema $schema) {
		$table = $schema->createTable(self::$tableNameReferences);
		$table->addColumn('referral_id', 'integer', []);
		$table->addColumn('referenced_id', 'integer', []);

		//INDEXES
		$table->setPrimaryKey(['referral_id', 'referenced_id'], "PRIMARY");
		$table->addIndex(['referenced_id'], 'idx_references_referenced', []);
		$table->addIndex(['referral_id'], 'idx_references_referral', []);

		//FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable(self::$tableNameReferenceable),
			['referral_id'],
			['id'],
			['onDelete' => null, 'onUpdate' => null],
			'fk_references_referral'
		);

		$table->addForeignKeyConstraint(
			$schema->getTable(self::$tableNameReferenceable),
			['referenced_id'],
			['id'],
			['onDelete' => null, 'onUpdate' => null],
			'fk_references_referenced'
		);
	}

	/**
	 * Creates column, index and fk for table in $referenceableTable
	 * @param String $tableName - the name of the table you want to reference
	 * @param String $columnName - the name of the column to use
	 * @param Table  $referenceableTable
	 * @param Schema $schema
	 */
	protected function createReferenceForTable($tableName, $columnName, Table $referenceableTable, Schema $schema) {
		$referenceableTable->addColumn($columnName, 'integer', ['notnull' => false]);
		$referenceableTable->addUniqueIndex([$columnName], 'uniq_referenceable_' . $tableName);
		$referenceableTable->addForeignKeyConstraint(
			$schema->getTable($tableName),
			[$columnName],
			['id'],
			['onDelete' => null, 'onUpdate' => null],
			'fk_referenceable_' . $tableName
		);
	}

}