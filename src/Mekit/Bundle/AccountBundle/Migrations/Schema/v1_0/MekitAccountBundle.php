<?php
namespace Mekit\Bundle\AccountBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * Class MekitAccountBundle (usage: oro:migration:load --show-queries --bundles MekitAccountBundle --dry-run)
 *
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 *
 */
class MekitAccountBundle implements Migration {
	/** @var string */
	public static $tableNameAccount = "mekit_account";


	/**
	 * @param Schema   $schema
	 * @param QueryBag $queries
	 */
	public function up(Schema $schema, QueryBag $queries) {
		/** Tables and foreign keys generation **/
		$this->setupEntityTableAccount($schema);
	}

	/**
	 * Create Account(mekit_account) table + foreign keys
	 *
	 * @param Schema $schema
	 */
	protected function setupEntityTableAccount(Schema $schema) {
		$table = $schema->createTable(self::$tableNameAccount);
		$table->addColumn('id', 'integer', ['autoincrement' => true]);
		$table->addColumn('owner_id', 'integer', ['notnull' => false]);
		$table->addColumn('organization_id', 'integer', ['notnull' => false]);
		$table->addColumn('createdAt', 'datetime', []);
		$table->addColumn('updatedAt', 'datetime', ['notnull' => false]);
		//
		$table->addColumn('name', 'string', ['length' => 255]);
		$table->addColumn('vatid', 'string', ['length' => 16]);
		$table->addColumn('nin', 'string', ['length' => 24]);
		$table->addColumn('website', 'string', ['length' => 128]);
		$table->addColumn('fax', 'string', ['length' => 16]);
		$table->addColumn('description', '65535', ['length' => 65535]);

		//INDEXES
		$table->setPrimaryKey(['id']);
		$table->addIndex(['owner_id'], 'idx_account_owner_id', []);
		$table->addIndex(['organization_id'], 'idx_account_organization', []);
		$table->addIndex(['createdAt'], 'idx_account_created_at', []);
		$table->addIndex(['updatedAt'], 'idx_account_updated_at', []);
		$table->addIndex(['name'], 'idx_account_name', []);
		$table->addIndex(['vatid'], 'idx_account_vatid', []);



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
	}


}