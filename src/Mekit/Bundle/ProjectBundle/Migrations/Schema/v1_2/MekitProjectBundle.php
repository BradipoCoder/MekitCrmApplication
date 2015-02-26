<?php
namespace Mekit\Bundle\ProjectBundle\Migrations\Schema\v1_2;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * Class MekitProjectBundle
 */
class MekitProjectBundle implements Migration {
	/**
	 * @param Schema $schema
	 * @param QueryBag $queries
	 */
	public function up(Schema $schema, QueryBag $queries) {
		$this->create_user_relations($schema);
		$this->create_task_relations($schema);
		$this->create_call_relations($schema);
		$this->create_meeting_relations($schema);
	}

	/**
	 * @param Schema $schema
	 * @throws \Doctrine\DBAL\Schema\SchemaException
	 */
	protected function create_user_relations(Schema $schema) {
		$relationTableName = "mekit_rel_project_user";
		$table = $schema->createTable($relationTableName);
		$table->addColumn('project_id', 'integer', ['notnull' => true]);
		$table->addColumn('user_id', 'integer', ['notnull' => true]);
		// INDEXES
		$table->setPrimaryKey(['project_id', 'user_id']);
		$table->addIndex(['project_id'], 'idx_project', []);
		$table->addIndex(['user_id'], 'idx_user', []);
		// FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_project'),
			['project_id'],
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
	protected function create_task_relations(Schema $schema) {
		$relationTableName = "mekit_rel_project_task";
		$table = $schema->createTable($relationTableName);
		$table->addColumn('project_id', 'integer', ['notnull' => true]);
		$table->addColumn('task_id', 'integer', ['notnull' => true]);
		// INDEXES
		$table->setPrimaryKey(['project_id', 'task_id']);
		$table->addIndex(['project_id'], 'idx_project', []);
		$table->addIndex(['task_id'], 'idx_task', []);
		// FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_project'),
			['project_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null]
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_task'),
			['task_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null]
		);
	}

	/**
	 * @param Schema $schema
	 * @throws \Doctrine\DBAL\Schema\SchemaException
	 */
	protected function create_call_relations(Schema $schema) {
		$relationTableName = "mekit_rel_project_call";
		$table = $schema->createTable($relationTableName);
		$table->addColumn('project_id', 'integer', ['notnull' => true]);
		$table->addColumn('call_id', 'integer', ['notnull' => true]);
		// INDEXES
		$table->setPrimaryKey(['project_id', 'call_id']);
		$table->addIndex(['project_id'], 'idx_project', []);
		$table->addIndex(['call_id'], 'idx_call', []);
		// FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_project'),
			['project_id'],
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
		$relationTableName = "mekit_rel_project_meeting";
		$table = $schema->createTable($relationTableName);
		$table->addColumn('project_id', 'integer', ['notnull' => true]);
		$table->addColumn('meeting_id', 'integer', ['notnull' => true]);
		// INDEXES
		$table->setPrimaryKey(['project_id', 'meeting_id']);
		$table->addIndex(['project_id'], 'idx_project', []);
		$table->addIndex(['meeting_id'], 'idx_meeting', []);
		// FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_project'),
			['project_id'],
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