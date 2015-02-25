<?php
namespace Mekit\Bundle\ContactBundle\Migrations\Schema\v1_1;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * Class MekitContactBundle
 */
class MekitContactBundle implements Migration {
	/**
	 * @param Schema $schema
	 * @param QueryBag $queries
	 */
	public function up(Schema $schema, QueryBag $queries) {
		$this->create_task_relations($schema);
		$this->create_call_relations($schema);
		$this->create_meeting_relations($schema);
	}

	/**
	 * @param Schema $schema
	 * @throws \Doctrine\DBAL\Schema\SchemaException
	 */
	protected function create_task_relations(Schema $schema) {
		$relationTableName = "mekit_rel_contact_task";
		$table = $schema->createTable($relationTableName);
		$table->addColumn('contact_id', 'integer', ['notnull' => true]);
		$table->addColumn('task_id', 'integer', ['notnull' => true]);
		// INDEXES
		$table->setPrimaryKey(['contact_id', 'task_id']);
		$table->addIndex(['contact_id'], 'idx_contact', []);
		$table->addIndex(['task_id'], 'idx_task', []);
		// FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_contact'),
			['contact_id'],
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
		$relationTableName = "mekit_rel_contact_call";
		$table = $schema->createTable($relationTableName);
		$table->addColumn('contact_id', 'integer', ['notnull' => true]);
		$table->addColumn('call_id', 'integer', ['notnull' => true]);
		// INDEXES
		$table->setPrimaryKey(['contact_id', 'call_id']);
		$table->addIndex(['contact_id'], 'idx_contact', []);
		$table->addIndex(['call_id'], 'idx_call', []);
		// FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_contact'),
			['contact_id'],
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
		$relationTableName = "mekit_rel_contact_meeting";
		$table = $schema->createTable($relationTableName);
		$table->addColumn('contact_id', 'integer', ['notnull' => true]);
		$table->addColumn('meeting_id', 'integer', ['notnull' => true]);
		// INDEXES
		$table->setPrimaryKey(['contact_id', 'meeting_id']);
		$table->addIndex(['contact_id'], 'idx_contact', []);
		$table->addIndex(['meeting_id'], 'idx_meeting', []);
		// FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_contact'),
			['contact_id'],
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