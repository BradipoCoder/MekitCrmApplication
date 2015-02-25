<?php
namespace Mekit\Bundle\AccountBundle\Migrations\Schema\v1_1;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * Class MekitAccountBundle
 */
class MekitAccountBundle implements Migration {
	/**
	 * @param Schema $schema
	 * @param QueryBag $queries
	 */
	public function up(Schema $schema, QueryBag $queries) {
		$this->create_user_relations($schema);
		$this->create_contact_relations($schema);
		$this->create_task_relations($schema);
		$this->create_call_relations($schema);
		$this->create_meeting_relations($schema);
	}

	protected function create_user_relations(Schema $schema) {
		$relationTableName = "mekit_rel_account_user";
		$table = $schema->createTable($relationTableName);
		$table->addColumn('account_id', 'integer', ['notnull' => true]);
		$table->addColumn('user_id', 'integer', ['notnull' => true]);
		// INDEXES
		$table->setPrimaryKey(['account_id', 'user_id']);
		$table->addIndex(['account_id'], 'idx_account', []);
		$table->addIndex(['user_id'], 'idx_user', []);
		// FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_account'),
			['account_id'],
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

	protected function create_contact_relations(Schema $schema) {
		$relationTableName = "mekit_rel_account_contact";
		$table = $schema->createTable($relationTableName);
		$table->addColumn('account_id', 'integer', ['notnull' => true]);
		$table->addColumn('contact_id', 'integer', ['notnull' => true]);
		// INDEXES
		$table->setPrimaryKey(['account_id', 'contact_id']);
		$table->addIndex(['account_id'], 'idx_account', []);
		$table->addIndex(['contact_id'], 'idx_contact', []);
		// FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_account'),
			['account_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null]
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_contact'),
			['contact_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null]
		);
	}

	protected function create_task_relations(Schema $schema) {
		$relationTableName = "mekit_rel_account_task";
		$table = $schema->createTable($relationTableName);
		$table->addColumn('account_id', 'integer', ['notnull' => true]);
		$table->addColumn('task_id', 'integer', ['notnull' => true]);
		// INDEXES
		$table->setPrimaryKey(['account_id', 'task_id']);
		$table->addIndex(['account_id'], 'idx_account', []);
		$table->addIndex(['task_id'], 'idx_task', []);
		// FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_account'),
			['account_id'],
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

	protected function create_call_relations(Schema $schema) {
		$relationTableName = "mekit_rel_account_call";
		$table = $schema->createTable($relationTableName);
		$table->addColumn('account_id', 'integer', ['notnull' => true]);
		$table->addColumn('call_id', 'integer', ['notnull' => true]);
		// INDEXES
		$table->setPrimaryKey(['account_id', 'call_id']);
		$table->addIndex(['account_id'], 'idx_account', []);
		$table->addIndex(['call_id'], 'idx_call', []);
		// FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_account'),
			['account_id'],
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

	protected function create_meeting_relations(Schema $schema) {
		$relationTableName = "mekit_rel_account_meeting";
		$table = $schema->createTable($relationTableName);
		$table->addColumn('account_id', 'integer', ['notnull' => true]);
		$table->addColumn('meeting_id', 'integer', ['notnull' => true]);
		// INDEXES
		$table->setPrimaryKey(['account_id', 'meeting_id']);
		$table->addIndex(['account_id'], 'idx_account', []);
		$table->addIndex(['meeting_id'], 'idx_meeting', []);
		// FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_account'),
			['account_id'],
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