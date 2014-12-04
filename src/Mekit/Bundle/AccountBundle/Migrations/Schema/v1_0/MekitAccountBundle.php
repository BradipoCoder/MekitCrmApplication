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
	public static $tableNameAccountAddress = "mekit_account_address";
	public static $tableNameAccountAddressToType = "mekit_account_adr_to_adr_type";
	public static $tableNameAccountEmail = "mekit_account_email";
	public static $tableNameAccountPhone = "mekit_account_phone";

	/**
	 * @param Schema   $schema
	 * @param QueryBag $queries
	 */
	public function up(Schema $schema, QueryBag $queries) {
		/** Tables and foreign keys generation **/
		$this->createMekitAccountTable($schema);
		$this->createMekitAccountAddressTable($schema);
		$this->createMekitAccountAdrToAdrTypeTable($schema);
		$this->createMekitAccountEmailTable($schema);
		$this->createMekitAccountPhoneTable($schema);
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
		$table->addColumn('vatid', 'string', ['length' => 16]);
		$table->addColumn('nin', 'string', ['length' => 24]);
		$table->addColumn('website', 'string', ['length' => 128]);
		$table->addColumn('fax', 'string', ['length' => 16]);
		$table->addColumn('description', 'string', ['length' => 65535]);
		$table->addColumn('source', 'string', ['length' => 32]);
		$table->addColumn('type', 'string', ['length' => 32]);
		$table->addColumn('state', 'string', ['length' => 32]);
		$table->addColumn('industry', 'string', ['length' => 32]);
		$table->addColumn('email', 'string', ['notnull' => false, 'length' => 255]);/*do we need this?*/

		//INDEXES
		$table->setPrimaryKey(['id']);
		$table->addIndex(['owner_id'], 'idx_account_owner_id', []);
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
			$schema->getTable('oro_user'),
			['assigned_to'],
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

	/**
	 * Create mekit_account_address table
	 *
	 * @param Schema $schema
	 */
	protected function createMekitAccountAddressTable(Schema $schema) {
		$table = $schema->createTable(self::$tableNameAccountAddress);
		$table->addColumn('id', 'integer', ['autoincrement' => true]);
		$table->addColumn('owner_id', 'integer', ['notnull' => false]);
		$table->addColumn('region_code', 'string', ['notnull' => false, 'length' => 16]);
		$table->addColumn('country_code', 'string', ['notnull' => false, 'length' => 2]);
		$table->addColumn('is_primary', 'boolean', ['notnull' => false]);
		$table->addColumn('label', 'string', ['notnull' => false, 'length' => 255]);
		$table->addColumn('street', 'string', ['notnull' => false, 'length' => 500]);
		$table->addColumn('street2', 'string', ['notnull' => false, 'length' => 500]);
		$table->addColumn('city', 'string', ['notnull' => false, 'length' => 255]);
		$table->addColumn('postal_code', 'string', ['notnull' => false, 'length' => 20]);
		$table->addColumn('organization', 'string', ['notnull' => false, 'length' => 255]);
		$table->addColumn('region_text', 'string', ['notnull' => false, 'length' => 255]);
		$table->addColumn('name_prefix', 'string', ['notnull' => false, 'length' => 255]);
		$table->addColumn('first_name', 'string', ['notnull' => false, 'length' => 255]);
		$table->addColumn('middle_name', 'string', ['notnull' => false, 'length' => 255]);
		$table->addColumn('last_name', 'string', ['notnull' => false, 'length' => 255]);
		$table->addColumn('name_suffix', 'string', ['notnull' => false, 'length' => 255]);
		$table->addColumn('created', 'datetime', []);
		$table->addColumn('updated', 'datetime', []);

		//INDEXES
		$table->setPrimaryKey(['id']);
		$table->addIndex(['owner_id'], 'idx_address_owner_id', []);
		$table->addIndex(['country_code'], 'idx_address_country_code', []);
		$table->addIndex(['region_code'], 'idx_address_region_code', []);

		//FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable(self::$tableNameAccount),
			['owner_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null],
			'fk_address_owner'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_dictionary_country'),
			['country_code'],
			['iso2_code'],
			['onDelete' => null, 'onUpdate' => null],
			'fk_address_country'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_dictionary_region'),
			['region_code'],
			['combined_code'],
			['onDelete' => null, 'onUpdate' => null],
			'fk_address_region'
		);
	}

	/**
	 * Create mekit_account_adr_to_adr_type table
	 *
	 * @param Schema $schema
	 */
	protected function createMekitAccountAdrToAdrTypeTable(Schema $schema) {
		$table = $schema->createTable(self::$tableNameAccountAddressToType);
		$table->addColumn('account_address_id', 'integer', []);
		$table->addColumn('type_name', 'string', ['length' => 16]);

		//INDEXES
		$table->setPrimaryKey(['account_address_id', 'type_name']);
		$table->addIndex(['account_address_id'], 'idx_addresstype_addressid', []);
		$table->addIndex(['type_name'], 'idx_addresstype_type_name', []);

		//FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable(self::$tableNameAccountAddress),
			['account_address_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null],
			'fk_addresstype_addressid'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_address_type'),
			['type_name'],
			['name'],
			['onDelete' => null, 'onUpdate' => null],
			'fk_addresstype_type_name'
		);
	}

	/**
	 * Create mekit_account_email table
	 *
	 * @param Schema $schema
	 */
	protected function createMekitAccountEmailTable(Schema $schema) {
		$table = $schema->createTable(self::$tableNameAccountEmail);
		$table->addColumn('id', 'integer', ['autoincrement' => true]);
		$table->addColumn('account_id', 'integer', ['notnull' => false]);
		$table->addColumn('contact_id', 'integer', ['notnull' => false]);
		$table->addColumn('email', 'string', ['length' => 255]);
		$table->addColumn('is_primary', 'boolean', ['notnull' => false]);

		//INDEXES
		$table->setPrimaryKey(['id']);
		$table->addIndex(['email', 'is_primary'], 'primary_email_idx', []);
		$table->addIndex(['account_id'], 'idx_email_account_id', []);
		$table->addIndex(['contact_id'], 'idx_email_contact_id', []);

		//FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable(self::$tableNameAccount),
			['account_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null],
			'fk_email_account'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_contact'),
			['contact_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null],
			'fk_email_contact'
		);
	}

	/**
	 * Create mekit_account_phone table
	 *
	 * @param Schema $schema
	 */
	protected function createMekitAccountPhoneTable(Schema $schema) {
		$table = $schema->createTable(self::$tableNameAccountPhone);
		$table->addColumn('id', 'integer', ['autoincrement' => true]);
		$table->addColumn('account_id', 'integer', ['notnull' => false]);
		$table->addColumn('contact_id', 'integer', ['notnull' => false]);
		$table->addColumn('phone', 'string', ['length' => 255]);
		$table->addColumn('is_primary', 'boolean', ['notnull' => false]);

		//INDEXES
		$table->setPrimaryKey(['id']);
		$table->addIndex(['phone', 'is_primary'], 'primary_phone_idx', []);
		$table->addIndex(['account_id'], 'idx_phone_account_id', []);
		$table->addIndex(['contact_id'], 'idx_phone_account_id', []);

		//FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable(self::$tableNameAccount),
			['account_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null],
			'fk_phone_account'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_contact'),
			['contact_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null],
			'fk_phone_contact'
		);
	}
}