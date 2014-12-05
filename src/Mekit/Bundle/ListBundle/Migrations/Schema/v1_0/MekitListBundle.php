<?php
namespace Mekit\Bundle\ListBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class MekitListBundle implements Migration {
	public static $tableNameListGroup = "mekit_list_group";
	public static $tableNameListItem = "mekit_list_item";

	/**
	 * @param Schema   $schema
	 * @param QueryBag $queries
	 */
	public function up(Schema $schema, QueryBag $queries) {
		/** Tables and foreign keys generation **/
		$this->createMekitListGroupTable($schema);
		$this->createMekitListItemTable($schema);
	}

	/**
	 * Create mekit_list_group table
	 *
	 * @param Schema $schema
	 */
	protected function createMekitListGroupTable(Schema $schema) {
		$table = $schema->createTable(self::$tableNameListGroup);
		$table->addColumn('id', 'integer', ['autoincrement' => true]);
		$table->addColumn('name', 'string', ['length' => 32]);
		$table->addColumn('label', 'string', ['length' => 255]);
		$table->addColumn('description', 'text', ['notnull' => false]);
		$table->addColumn('itemPrefix', 'string', ['length' => 8]);
		$table->addColumn('emptyValue', 'string', ['notnull' => false, 'length' => 64]);
		$table->addColumn('required', 'boolean', ['default' => '0']);
		$table->addColumn('system', 'boolean', ['default' => '0']);

		//INDEXES
		$table->setPrimaryKey(['id']);
		$table->addUniqueIndex(['name'], 'idx_listgroup_name');
		$table->addUniqueIndex(['itemPrefix'], 'idx_listgroup_item_prefix');
		$table->addIndex(['system'], 'idx_listgroup_system', []);
	}

	/**
	 * Create mekit_list_item table
	 *
	 * @param Schema $schema
	 */
	protected function createMekitListItemTable(Schema $schema) {
		$table = $schema->createTable(self::$tableNameListItem);
		$table->addColumn('id', 'string', ['length' => 32]);
		$table->addColumn('listgroup_id', 'integer', ['notnull' => false]);
		$table->addColumn('label', 'string', ['length' => 255]);
		$table->addColumn('default_item', 'boolean', ['default' => '0']);
		$table->addColumn('system', 'boolean', ['default' => '0']);

		//INDEXES
		$table->setPrimaryKey(['id']);
		$table->addIndex(['listgroup_id'], 'idx_listitem_listgroup_id', []);
		$table->addIndex(['system'], 'idx_listitem_system', []);

		//FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable(self::$tableNameListGroup),
			['listgroup_id'],
			['id'],
			['onDelete' => 'SET NULL', 'onUpdate' => null],
			'fk_listitem_listgroup'
		);
	}
}