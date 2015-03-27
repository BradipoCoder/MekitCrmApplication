<?php
namespace Mekit\Bundle\WorklogBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class MekitWorklogBundle implements Migration
{
	public static $tableNameWorklog = "mekit_worklog";

	/**
	 * @param Schema   $schema
	 * @param QueryBag $queries
	 */
	public function up(Schema $schema, QueryBag $queries) {
		/** Tables and foreign keys generation **/
		$this->createMekitWorklogTable($schema);
	}

	/**
	 * Create mekit_worklog table
	 *
	 * @param Schema $schema
	 */
	protected function createMekitWorklogTable(Schema $schema) {
		$table = $schema->createTable(self::$tableNameWorklog);
		$table->addColumn('id', 'integer', ['autoincrement' => true]);
		$table->addColumn('execution_date', 'datetime', []);
		$table->addColumn('duration', 'integer', []);
		$table->addColumn('description', 'text', []);
		$table->addColumn('task_id', 'integer', []);
		$table->addColumn('owner_id', 'integer', ['notnull' => false]);
		$table->addColumn('createdAt', 'datetime', []);
		$table->addColumn('updatedAt', 'datetime', ['notnull' => false]);

		//INDEXES
		$table->setPrimaryKey(['id']);
		$table->addIndex(['execution_date'], 'idx_worklog_exec_date', []);
		$table->addIndex(['task_id'], 'idx_worklog_task', []);
		$table->addIndex(['owner_id'], 'idx_worklog_owner', []);

		//FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_user'),
			['owner_id'],
			['id'],
			['onDelete' => 'SET NULL', 'onUpdate' => null],
			'fk_worklog_owner'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_task'),
			['task_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null],
			'fk_worklog_task'
		);
	}
}