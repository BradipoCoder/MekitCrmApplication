<?php
namespace Mekit\Bundle\EventBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class MekitEventBundle implements Migration {
	public static $tableNameEvent = "mekit_event";

	/**
	 * @param Schema   $schema
	 * @param QueryBag $queries
	 */
	public function up(Schema $schema, QueryBag $queries) {
		/** Tables and foreign keys generation **/
		$this->createMekitEventTable($schema);
	}

	/**
	 * Create mekit_event table
	 *
	 * @param Schema $schema
	 */
	protected function createMekitEventTable(Schema $schema) {
		$table = $schema->createTable(self::$tableNameEvent);
		$table->addColumn('id', 'integer', ['autoincrement' => true]);
		$table->addColumn('owner_id', 'integer', ['notnull' => false]);
		$table->addColumn('organization_id', 'integer', ['notnull' => false]);
		$table->addColumn('createdAt', 'datetime', []);
		$table->addColumn('updatedAt', 'datetime', ['notnull' => false]);
		$table->addColumn('type', 'string', ['length' => 255]);
		$table->addColumn('start_date', 'datetime', []);
		$table->addColumn('end_date', 'datetime', ['notnull' => false]);
		$table->addColumn('duration', 'integer', ['notnull' => false]);
		$table->addColumn('priority', 'integer', ['notnull' => true]);
		$table->addColumn('state', 'integer', ['notnull' => true]);
		$table->addColumn('description', 'text', ['notnull' => false]);

		//INDEXES
		$table->setPrimaryKey(['id']);
		$table->addIndex(['owner_id'], 'idx_event_owner', []);
		$table->addIndex(['organization_id'], 'idx_event_organization', []);
		$table->addIndex(['createdAt'], 'idx_event_created_at', []);
		$table->addIndex(['updatedAt'], 'idx_event_updated_at', []);
		$table->addIndex(['type'], 'idx_event_type', []);
		$table->addIndex(['start_date'], 'idx_event_start_date', []);
		$table->addIndex(['end_date'], 'idx_event_end_date', []);
		$table->addIndex(['state'], 'idx_event_state', []);
		$table->addIndex(['priority'], 'idx_event_priority', []);

		//FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_user'),
			['owner_id'],
			['id'],
			['onDelete' => 'SET NULL', 'onUpdate' => null],
			'fk_event_owner'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_organization'),
			['organization_id'],
			['id'],
			['onDelete' => 'SET NULL', 'onUpdate' => null],
			'fk_event_organization'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_list_item'),
			['state'],
			['id'],
			['onDelete' => null, 'onUpdate' => null],
			'fk_event_state'
		);

		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_list_item'),
			['priority'],
			['id'],
			['onDelete' => null, 'onUpdate' => null],
			'fk_event_priority'
		);
	}
}