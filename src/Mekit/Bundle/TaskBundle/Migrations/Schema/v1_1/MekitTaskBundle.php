<?php
namespace Mekit\Bundle\TaskBundle\Migrations\Schema\v1_1;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * Class MekitTaskBundle
 */
class MekitTaskBundle implements Migration {
	/**
	 * @param Schema $schema
	 * @param QueryBag $queries
	 */
	public function up(Schema $schema, QueryBag $queries) {
		$this->create_user_relations($schema);
		$this->create_call_relations($schema);
		$this->create_meeting_relations($schema);
	}

	/**
	 * @param Schema $schema
	 * @throws \Doctrine\DBAL\Schema\SchemaException
	 */
	protected function create_user_relations(Schema $schema) {
		$relationTableName = "mekit_rel_task_user";
		$table = $schema->createTable($relationTableName);
		$table->addColumn('task_id', 'integer', ['notnull' => true]);
		$table->addColumn('user_id', 'integer', ['notnull' => true]);
		// INDEXES
		$table->setPrimaryKey(['task_id', 'user_id']);
		$table->addIndex(['task_id'], 'idx_task', []);
		$table->addIndex(['user_id'], 'idx_user', []);
		// FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_task'),
			['task_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null]
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_user'),
			['user_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null]
		);
	}

	/**
	 * @param Schema $schema
	 * @throws \Doctrine\DBAL\Schema\SchemaException
	 */
	protected function create_call_relations(Schema $schema) {
		$relationTableName = "mekit_rel_task_call";
		$table = $schema->createTable($relationTableName);
		$table->addColumn('task_id', 'integer', ['notnull' => true]);
		$table->addColumn('call_id', 'integer', ['notnull' => true]);
		// INDEXES
		$table->setPrimaryKey(['task_id', 'call_id']);
		$table->addIndex(['task_id'], 'idx_task', []);
		$table->addIndex(['call_id'], 'idx_call', []);
		// FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_task'),
			['task_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null]
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_call'),
			['call_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null]
		);
	}

	/**
	 * @param Schema $schema
	 * @throws \Doctrine\DBAL\Schema\SchemaException
	 */
	protected function create_meeting_relations(Schema $schema) {
		$relationTableName = "mekit_rel_task_meeting";
		$table = $schema->createTable($relationTableName);
		$table->addColumn('task_id', 'integer', ['notnull' => true]);
		$table->addColumn('meeting_id', 'integer', ['notnull' => true]);
		// INDEXES
		$table->setPrimaryKey(['task_id', 'meeting_id']);
		$table->addIndex(['task_id'], 'idx_task', []);
		$table->addIndex(['meeting_id'], 'idx_meeting', []);
		// FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_task'),
			['task_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null]
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_meeting'),
			['meeting_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null]
		);
	}

}