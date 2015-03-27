<?php
namespace Mekit\Bundle\TaskBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class MekitTaskBundle implements Migration {
	public static $tableNameTask = "mekit_task";

	/**
	 * @param Schema   $schema
	 * @param QueryBag $queries
	 */
	public function up(Schema $schema, QueryBag $queries) {
		/** Tables and foreign keys generation **/
		$this->createMekitTaskTable($schema);
	}

	/**
	 * Create mekit_task table
	 *
	 * @param Schema $schema
	 */
	protected function createMekitTaskTable(Schema $schema) {
		$table = $schema->createTable(self::$tableNameTask);
		$table->addColumn('id', 'integer', ['autoincrement' => true]);
		$table->addColumn('event_id', 'integer', []);
		$table->addColumn('name', 'string', ['length' => 255]);
		$table->addColumn('type', 'integer', ['notnull' => false]);
		$table->addColumn('project_id', 'integer', ['notnull' => false]);
		$table->addColumn('owner_id', 'integer', ['notnull' => false]);
		$table->addColumn('organization_id', 'integer', ['notnull' => false]);

		//INDEXES
		$table->setPrimaryKey(['id']);
		$table->addIndex(['owner_id'], 'idx_task_owner', []);
		$table->addIndex(['organization_id'], 'idx_task_organization', []);
		$table->addIndex(['name'], 'idx_task_name', []);
		$table->addIndex(['type'], 'idx_task_type', []);
		$table->addUniqueIndex(['event_id'], 'idx_task_event', []);
		$table->addIndex(['project_id'], 'idx_task_project', []);

		//FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_user'),
			['owner_id'],
			['id'],
			['onDelete' => 'SET NULL', 'onUpdate' => null],
			'fk_task_owner'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_organization'),
			['organization_id'],
			['id'],
			['onDelete' => 'SET NULL', 'onUpdate' => null],
			'fk_task_organization'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_event'),
			['event_id'],
			['id'],
			['onDelete' => 'CASCADE'],
			'fk_task_event'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_list_item'),
			['type'],
			['id'],
			['onDelete' => null, 'onUpdate' => null],
			'fk_task_type'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_project'),
			['project_id'],
			['id'],
			['onDelete' => 'CASCADE'],
			'fk_task_project'
		);
	}
}