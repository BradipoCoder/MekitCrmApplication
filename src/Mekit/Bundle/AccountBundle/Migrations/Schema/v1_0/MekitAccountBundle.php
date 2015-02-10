<?php
namespace Mekit\Bundle\AccountBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * usage: oro:migration:load --show-queries --bundles MekitAccountBundle --dry-run
 *
 * Class MekitAccountBundle
 */
class MekitAccountBundle implements Migration {
	public static $tableNameAccount = "mekit_account";

	/**
	 * @param Schema   $schema
	 * @param QueryBag $queries
	 */
	public function up(Schema $schema, QueryBag $queries) {
		/** Tables and foreign keys generation **/
		$this->createMekitAccountTable($schema);
	}

	/**
	 * Create Account(mekit_account) table + foreign keys
	 *
	 * @param Schema $schema
	 */
	protected function createMekitAccountTable(Schema $schema) {
		$table = $schema->createTable(self::$tableNameAccount);
		$table->addColumn('id', 'integer', ['autoincrement' => true]);
		$table->addColumn('owner_id', 'integer', ['notnull' => false]);
		$table->addColumn('organization_id', 'integer', ['notnull' => false]);
		$table->addColumn('createdAt', 'datetime', []);
		$table->addColumn('updatedAt', 'datetime', ['notnull' => false]);
		//
		$table->addColumn('name', 'string', ['length' => 255]);
		$table->addColumn('vatid', 'string', ['length' => 16, 'notnull' => false]);
		$table->addColumn('nin', 'string', ['length' => 24, 'notnull' => false]);
		$table->addColumn('website', 'string', ['length' => 128, 'notnull' => false]);
		$table->addColumn('fax', 'string', ['length' => 16, 'notnull' => false]);
		$table->addColumn('description', 'text', ['notnull' => false]);
		$table->addColumn('source', 'string', ['length' => 32]);
		$table->addColumn('type', 'string', ['length' => 32]);
		$table->addColumn('state', 'string', ['length' => 32]);
		$table->addColumn('industry', 'string', ['length' => 32]);

		//INDEXES
		$table->setPrimaryKey(['id']);
		$table->addIndex(['owner_id'], 'idx_account_owner', []);
		$table->addIndex(['organization_id'], 'idx_account_organization', []);
		$table->addIndex(['createdAt'], 'idx_account_created_at', []);
		$table->addIndex(['updatedAt'], 'idx_account_updated_at', []);
		$table->addIndex(['name'], 'idx_account_name', []);
		$table->addIndex(['vatid'], 'idx_account_vatid', []);
		$table->addIndex(['source'], 'idx_account_source', []);
		$table->addIndex(['type'], 'idx_account_type', []);
		$table->addIndex(['state'], 'idx_account_state', []);
		$table->addIndex(['industry'], 'idx_account_industry', []);

		//FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_organization'),
			['organization_id'],
			['id'],
			['onDelete' => 'SET NULL', 'onUpdate' => null],
			'fk_account_organization'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_user'),
			['owner_id'],
			['id'],
			['onDelete' => 'SET NULL', 'onUpdate' => null],
			'fk_account_owner'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_list_item'),
			['source'],
			['id'],
			['onDelete' => null, 'onUpdate' => null],
			'fk_account_source'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_list_item'),
			['type'],
			['id'],
			['onDelete' => null, 'onUpdate' => null],
			'fk_account_type'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_list_item'),
			['state'],
			['id'],
			['onDelete' => null, 'onUpdate' => null],
			'fk_account_state'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_list_item'),
			['industry'],
			['id'],
			['onDelete' => null, 'onUpdate' => null],
			'fk_account_industry'
		);
	}
}