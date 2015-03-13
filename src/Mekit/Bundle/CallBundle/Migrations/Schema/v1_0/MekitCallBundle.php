<?php
namespace Mekit\Bundle\CallBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class MekitCallBundle implements Migration {
	public static $tableNameCall = "mekit_call";

	/**
	 * @param Schema   $schema
	 * @param QueryBag $queries
	 */
	public function up(Schema $schema, QueryBag $queries) {
		/** Tables and foreign keys generation **/
		$this->createMekitCallTable($schema);
	}

	/**
	 * Create mekit_call table
	 *
	 * @param Schema $schema
	 */
	protected function createMekitCallTable(Schema $schema) {
		$table = $schema->createTable(self::$tableNameCall);
		$table->addColumn('id', 'integer', ['autoincrement' => true]);
		$table->addColumn('event_id', 'integer', []);
		$table->addColumn('name', 'string', ['length' => 255]);
		$table->addColumn('direction', 'string', ['length' => 4]);
		$table->addColumn('outcome', 'integer', ['notnull' => false]);
		$table->addColumn('owner_id', 'integer', ['notnull' => false]);
		$table->addColumn('organization_id', 'integer', ['notnull' => false]);


		//INDEXES
		$table->setPrimaryKey(['id']);
		$table->addIndex(['owner_id'], 'idx_call_owner', []);
		$table->addIndex(['organization_id'], 'idx_call_organization', []);
		$table->addIndex(['name'], 'idx_call_name', []);
		$table->addUniqueIndex(['event_id'], 'idx_call_event', []);
		$table->addIndex(['direction'], 'idx_call_direction', []);
		$table->addIndex(['outcome'], 'idx_call_outcome', []);


		//FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_user'),
			['owner_id'],
			['id'],
			['onDelete' => 'SET NULL', 'onUpdate' => null],
			'fk_call_owner'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_organization'),
			['organization_id'],
			['id'],
			['onDelete' => 'SET NULL', 'onUpdate' => null],
			'fk_call_organization'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_event'),
			['event_id'],
			['id'],
			['onDelete' => 'CASCADE'],
			'fk_call_event'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_list_item'),
			['outcome'],
			['id'],
			['onDelete' => null, 'onUpdate' => null],
			'fk_call_outcome'
		);
	}
}