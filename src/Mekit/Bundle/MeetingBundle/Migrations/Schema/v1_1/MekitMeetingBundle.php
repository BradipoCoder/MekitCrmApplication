<?php
namespace Mekit\Bundle\MeetingBundle\Migrations\Schema\v1_1;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * Class MekitMeetingBundle
 */
class MekitMeetingBundle implements Migration {
	/**
	 * @param Schema $schema
	 * @param QueryBag $queries
	 */
	public function up(Schema $schema, QueryBag $queries) {
		$this->create_user_relations($schema);
		$this->create_contact_relations($schema);
		$this->create_account_relations($schema);
		$this->create_project_relations($schema);
	}

	/**
	 * @param Schema $schema
	 * @throws \Doctrine\DBAL\Schema\SchemaException
	 */
	protected function create_user_relations(Schema $schema) {
		$relationTableName = "mekit_rel_meeting_user";
		$table = $schema->createTable($relationTableName);
		$table->addColumn('meeting_id', 'integer', ['notnull' => true]);
		$table->addColumn('user_id', 'integer', ['notnull' => true]);
		// INDEXES
		$table->setPrimaryKey(['meeting_id', 'user_id']);
		$table->addIndex(['meeting_id'], 'idx_meeting', []);
		$table->addIndex(['user_id'], 'idx_user', []);
		// FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_meeting'),
			['meeting_id'],
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
	protected function create_contact_relations(Schema $schema) {
		$relationTableName = "mekit_rel_meeting_contact";
		$table = $schema->createTable($relationTableName);
		$table->addColumn('meeting_id', 'integer', ['notnull' => true]);
		$table->addColumn('contact_id', 'integer', ['notnull' => true]);
		// INDEXES
		$table->setPrimaryKey(['meeting_id', 'contact_id']);
		$table->addIndex(['meeting_id'], 'idx_meeting', []);
		$table->addIndex(['contact_id'], 'idx_contact', []);
		// FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_meeting'),
			['meeting_id'],
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

	/**
	 * @param Schema $schema
	 * @throws \Doctrine\DBAL\Schema\SchemaException
	 */
	protected function create_account_relations(Schema $schema) {
		$relationTableName = "mekit_rel_meeting_account";
		$table = $schema->createTable($relationTableName);
		$table->addColumn('meeting_id', 'integer', ['notnull' => true]);
		$table->addColumn('account_id', 'integer', ['notnull' => true]);
		// INDEXES
		$table->setPrimaryKey(['meeting_id', 'account_id']);
		$table->addIndex(['meeting_id'], 'idx_meeting', []);
		$table->addIndex(['account_id'], 'idx_account', []);
		// FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_meeting'),
			['meeting_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null]
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_account'),
			['account_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null]
		);
	}

	/**
	 * @param Schema $schema
	 * @throws \Doctrine\DBAL\Schema\SchemaException
	 */
	protected function create_project_relations(Schema $schema) {
		$relationTableName = "mekit_rel_meeting_project";
		$table = $schema->createTable($relationTableName);
		$table->addColumn('meeting_id', 'integer', ['notnull' => true]);
		$table->addColumn('project_id', 'integer', ['notnull' => true]);
		// INDEXES
		$table->setPrimaryKey(['meeting_id', 'project_id']);
		$table->addIndex(['meeting_id'], 'idx_meeting', []);
		$table->addIndex(['project_id'], 'idx_project', []);
		// FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_meeting'),
			['meeting_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null]
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_project'),
			['project_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null]
		);
	}
}