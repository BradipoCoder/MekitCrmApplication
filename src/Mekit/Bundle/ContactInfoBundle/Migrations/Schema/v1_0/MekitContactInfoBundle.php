<?php
namespace Mekit\Bundle\ContactInfoBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class MekitContactInfoBundle implements Migration {
	public static $tableNameAddress = "mekit_address";
	public static $tableNameEmail = "mekit_email";
	public static $tableNamePhone = "mekit_phone";

	/**
	 * @param Schema   $schema
	 * @param QueryBag $queries
	 */
	public function up(Schema $schema, QueryBag $queries) {
		/** Tables and foreign keys generation **/
		$this->createMekitAddressTable($schema);
		$this->createMekitEmailTable($schema);
		$this->createMekitPhoneTable($schema);
	}

	/**
	 * Create mekit_address table
	 *
	 * @param Schema $schema
	 */
	protected function createMekitAddressTable(Schema $schema) {
		$table = $schema->createTable(self::$tableNameAddress);
		$table->addColumn('id', 'integer', ['autoincrement' => true]);
		$table->addColumn('account_id', 'integer', ['notnull' => false]);
		$table->addColumn('contact_id', 'integer', ['notnull' => false]);
		$table->addColumn('region_code', 'string', ['notnull' => false, 'length' => 16]);
		$table->addColumn('country_code', 'string', ['notnull' => false, 'length' => 2]);
		$table->addColumn('type', 'string', ['notnull' => false, 'length' => 16]);
		$table->addColumn('is_primary', 'boolean', ['notnull' => false]);
		$table->addColumn('label', 'string', ['notnull' => false, 'length' => 255]);
		$table->addColumn('street', 'string', ['notnull' => false, 'length' => 500]);
		$table->addColumn('street2', 'string', ['notnull' => false, 'length' => 500]);
		$table->addColumn('city', 'string', ['notnull' => false, 'length' => 255]);
		$table->addColumn('postal_code', 'string', ['notnull' => false, 'length' => 255]);
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
		$table->addIndex(['account_id'], 'idx_address_account', []);
		$table->addIndex(['contact_id'], 'idx_address_contact', []);
		$table->addIndex(['country_code'], 'idx_address_countrycode', []);
		$table->addIndex(['region_code'], 'idx_address_regioncode', []);

		//FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_account'),
			['account_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null],
			'fk_address_account'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_contact'),
			['contact_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null],
			'fk_address_contact'
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
	 * Create mekit_email table
	 *
	 * @param Schema $schema
	 */
	protected function createMekitEmailTable(Schema $schema) {
		$table = $schema->createTable(self::$tableNameEmail);
		$table->addColumn('id', 'integer', ['autoincrement' => true]);
		$table->addColumn('account_id', 'integer', ['notnull' => false]);
		$table->addColumn('contact_id', 'integer', ['notnull' => false]);
		$table->addColumn('email', 'string', ['length' => 255]);
		$table->addColumn('is_primary', 'boolean', ['notnull' => false]);

		//INDEXES
		$table->setPrimaryKey(['id']);
		$table->addIndex(['account_id'], 'idx_email_account', []);
		$table->addIndex(['contact_id'], 'idx_email_contact', []);
		$table->addIndex(['email', 'is_primary'], 'idx_email_primary', []);

		//FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_account'),
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
	 * Create mekit_phone table
	 *
	 * @param Schema $schema
	 */
	protected function createMekitPhoneTable(Schema $schema) {
		$table = $schema->createTable(self::$tableNamePhone);
		$table->addColumn('id', 'integer', ['autoincrement' => true]);
		$table->addColumn('account_id', 'integer', ['notnull' => false]);
		$table->addColumn('contact_id', 'integer', ['notnull' => false]);
		$table->addColumn('phone', 'string', ['length' => 255]);
		$table->addColumn('is_primary', 'boolean', ['notnull' => false]);

		//INDEXES
		$table->setPrimaryKey(['id']);
		$table->addIndex(['account_id'], 'idx_phone_account', []);
		$table->addIndex(['contact_id'], 'idx_phone_contact', []);
		$table->addIndex(['phone', 'is_primary'], 'idx_phone_primary', []);

		//FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_account'),
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