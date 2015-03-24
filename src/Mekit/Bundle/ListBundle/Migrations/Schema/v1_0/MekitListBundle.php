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
		$table->addColumn('name', 'string', ['length' => 32, 'notnull' => true]);
		$table->addColumn('label', 'string', ['length' => 255]);
		$table->addColumn('description', 'text', ['notnull' => false]);
		$table->addColumn('emptyValue', 'string', ['notnull' => false, 'length' => 64]);
		$table->addColumn('required', 'boolean', ['default' => '0']);
		$table->addColumn('system', 'boolean', ['default' => '0']);
		$table->addColumn('business_unit_id', 'integer', ['notnull' => false]);
		$table->addColumn('organization_id', 'integer', ['notnull' => false]);

		//INDEXES
		$table->setPrimaryKey(['id']);
		$table->addUniqueIndex(['name'], 'idx_lg_name');
		$table->addIndex(['system'], 'idx_lg_system', []);
		$table->addIndex(['business_unit_id'], 'idx_lg_owner', []);
		$table->addIndex(['organization_id'], 'idx_lg_organization', []);

		//FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_business_unit'),
			['business_unit_id'],
			['id'],
			['onDelete' => 'SET NULL'],
			'fk_lg_owner'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_organization'),
			['organization_id'],
			['id'],
			['onDelete' => 'SET NULL'],
			'fk_lg_organization'
		);

	}

	/**
	 * Create mekit_list_item table
	 *
	 * @param Schema $schema
	 */
	protected function createMekitListItemTable(Schema $schema) {
		$table = $schema->createTable(self::$tableNameListItem);
		$table->addColumn('id', 'integer', ['autoincrement' => true]);
		$table->addColumn('listgroup_id', 'integer', ['notnull' => false]);
		$table->addColumn('name', 'string', ['length' => 32, 'notnull' => true]);
		$table->addColumn('label', 'string', ['length' => 255]);
		$table->addColumn('default_item', 'boolean', ['default' => '0']);
		$table->addColumn('system', 'boolean', ['default' => '0']);
		$table->addColumn('business_unit_id', 'integer', ['notnull' => false]);
		$table->addColumn('organization_id', 'integer', ['notnull' => false]);

		//INDEXES
		$table->setPrimaryKey(['id']);
		$table->addIndex(['name'], 'idx_li_name', []);
		$table->addIndex(['listgroup_id'], 'idx_li_listgroup', []);
		$table->addIndex(['system'], 'idx_li_system', []);
		$table->addIndex(['default_item'], 'idx_li_default', []);
		$table->addIndex(['business_unit_id'], 'idx_li_owner', []);
		$table->addIndex(['organization_id'], 'idx_li_organization', []);

		//FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable(self::$tableNameListGroup),
			['listgroup_id'],
			['id'],
			['onDelete' => 'CASCADE'],
			'fk_li_listgroup'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_business_unit'),
			['business_unit_id'],
			['id'],
			['onDelete' => 'SET NULL'],
			'fk_li_owner'
		);

		$table->addForeignKeyConstraint(
			$schema->getTable('oro_organization'),
			['organization_id'],
			['id'],
			['onDelete' => 'SET NULL'],
			'fk_li_organization'
		);
	}
}