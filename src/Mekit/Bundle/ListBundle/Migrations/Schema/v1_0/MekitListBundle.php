<?php
namespace Mekit\Bundle\ListBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class MekitListBundle implements Migration {
	/** @var string */
	public static $tableNameListGroup = "mekit_list_group";

	/** @var string */
	public static $tableNameListItem = "mekit_list_item";

	/**
	 * @param Schema   $schema
	 * @param QueryBag $queries
	 */
	public function up(Schema $schema, QueryBag $queries) {
		/** Tables and foreign keys generation **/
		$this->setupEntityTableListGroup($schema);
		$this->setupEntityTableListItem($schema);
	}

	/**
	 * Setup ListGroup(mekit_list_group)
	 *
	 * @param Schema $schema
	 */
	protected function setupEntityTableListGroup(Schema $schema) {
		$table = $schema->createTable(self::$tableNameListGroup);
		$table->addColumn('id', 'integer', ['autoincrement' => true]);
		$table->addColumn('name', 'string', ['length' => 32]);
		$table->addColumn('label', 'string', ['length' => 255]);
		$table->addColumn('description', 'text', ['notnull' => false]);
		$table->addColumn('createdAt', 'datetime', []);
		$table->addColumn('updatedAt', 'datetime', ['notnull' => false]);

		//INDEXES
		$table->setPrimaryKey(['id']);
		$table->addUniqueIndex(['name'], 'idx_listgroup_name');
		$table->addIndex(['createdAt'], 'idx_listgroup_created_at', []);
		$table->addIndex(['updatedAt'], 'idx_listgroup_updated_at', []);
	}

	/**
	 * Setup ListItem(mekit_list_item)
	 *
	 * @param Schema $schema
	 */
	protected function setupEntityTableListItem(Schema $schema) {
		$table = $schema->createTable(self::$tableNameListItem);
		$table->addColumn('id', 'string', ['length' => 32]);
		$table->addColumn('listgroup_id', 'integer', ['notnull' => false]);
		$table->addColumn('label', 'string', ['length' => 255]);
		$table->addColumn('createdAt', 'datetime', []);
		$table->addColumn('updatedAt', 'datetime', ['notnull' => false]);

		//INDEXES
		$table->setPrimaryKey(['id']);
		$table->addIndex(['listgroup_id'], 'idx_listgroup_id', []);
		$table->addIndex(['createdAt'], 'idx_listitem_created_at', []);
		$table->addIndex(['updatedAt'], 'idx_listitem_updated_at', []);

		//FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable(self::$tableNameListGroup),
			['listgroup_id'],
			['id'],
			['onDelete' => 'SET NULL', 'onUpdate' => null]
		);
	}
}