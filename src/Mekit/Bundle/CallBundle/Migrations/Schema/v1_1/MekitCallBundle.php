<?php
namespace Mekit\Bundle\CallBundle\Migrations\Schema\v1_1;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * Class MekitCallBundle
 */
class MekitCallBundle implements Migration {
	/**
	 * @param Schema $schema
	 * @param QueryBag $queries
	 */
	public function up(Schema $schema, QueryBag $queries) {
		$this->create_meeting_relations($schema);
	}

	/**
	 * @param Schema $schema
	 * @throws \Doctrine\DBAL\Schema\SchemaException
	 */
	protected function create_meeting_relations(Schema $schema) {
		$relationTableName = "mekit_rel_call_meeting";
		$table = $schema->createTable($relationTableName);
		$table->addColumn('call_id', 'integer', ['notnull' => true]);
		$table->addColumn('meeting_id', 'integer', ['notnull' => true]);
		// INDEXES
		$table->setPrimaryKey(['call_id', 'meeting_id']);
		$table->addIndex(['call_id'], 'idx_call', []);
		$table->addIndex(['meeting_id'], 'idx_meeting', []);
		// FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_call'),
			['call_id'],
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