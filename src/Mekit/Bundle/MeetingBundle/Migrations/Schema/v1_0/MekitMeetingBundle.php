<?php
namespace Mekit\Bundle\MeetingBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class MekitMeetingBundle implements Migration {
	public static $tableNameMeeting = "mekit_meeting";

	/**
	 * @param Schema   $schema
	 * @param QueryBag $queries
	 */
	public function up(Schema $schema, QueryBag $queries) {
		/** Tables and foreign keys generation **/
		$this->createMekitMeetingTable($schema);
	}

	/**
	 * Create mekit_meeting table
	 *
	 * @param Schema $schema
	 */
	protected function createMekitMeetingTable(Schema $schema) {
		$table = $schema->createTable(self::$tableNameMeeting);
		$table->addColumn('id', 'integer', ['autoincrement' => true]);
		$table->addColumn('event_id', 'integer', []);
		$table->addColumn('name', 'string', ['length' => 255]);
		$table->addColumn('owner_id', 'integer', ['notnull' => false]);
		$table->addColumn('organization_id', 'integer', ['notnull' => false]);

		//INDEXES
		$table->setPrimaryKey(['id']);
		$table->addIndex(['owner_id'], 'idx_meeting_owner', []);
		$table->addIndex(['organization_id'], 'idx_meeting_organization', []);
		$table->addIndex(['name'], 'idx_meeting_name', []);
		$table->addUniqueIndex(['event_id'], 'idx_meeting_event', []);

		//FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_user'),
			['owner_id'],
			['id'],
			['onDelete' => 'SET NULL', 'onUpdate' => null],
			'fk_meeting_owner'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_organization'),
			['organization_id'],
			['id'],
			['onDelete' => 'SET NULL', 'onUpdate' => null],
			'fk_meeting_organization'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_event'),
			['event_id'],
			['id'],
			['onDelete' => 'CASCADE'],
			'fk_meeting_event'
		);
	}
}