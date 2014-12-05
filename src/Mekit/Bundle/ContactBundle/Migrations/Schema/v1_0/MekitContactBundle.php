<?php

namespace Mekit\Bundle\ContactBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * Class MekitContactBundle
 */
class MekitContactBundle implements Migration {
	public static $tableNameContact = "mekit_contact";
	public static $tableNameContactAddress = "mekit_contact_address";
	public static $tableNameContactAddressToType = "mekit_contact_adr_to_adr_type";
	public static $tableNameContactEmail = "mekit_contact_email";
	public static $tableNameContactPhone = "mekit_contact_phone";

	/**
	 * @param Schema   $schema
	 * @param QueryBag $queries
	 */
	public function up(Schema $schema, QueryBag $queries) {
		/** Tables and foreign keys generation **/
		$this->createMekitContactTable($schema);
		$this->createMekitContactAddressTable($schema);
		$this->createMekitContactAdrToAdrTypeTable($schema);
		$this->createMekitContactEmailTable($schema);
		$this->createMekitContactPhoneTable($schema);
	}

	/**
	 * Create mekit_contact table
	 *
	 * @param Schema $schema
	 */
	protected function createMekitContactTable(Schema $schema) {
		$table = $schema->createTable(self::$tableNameContact);
		$table->addColumn('id', 'integer', ['autoincrement' => true]);
		$table->addColumn('job_title', 'string', ['notnull' => false, 'length' => 32]);
		$table->addColumn('organization_id', 'integer', ['notnull' => false]);
		$table->addColumn('owner_id', 'integer', ['notnull' => false]);
		$table->addColumn('assigned_to', 'integer', ['notnull' => false]);
		$table->addColumn('account_id', 'integer', ['notnull' => false]);
		$table->addColumn('name_prefix', 'string', ['notnull' => false, 'length' => 16]);
		$table->addColumn('first_name', 'string', ['length' => 128]);
		$table->addColumn('middle_name', 'string', ['notnull' => false, 'length' => 64]);
		$table->addColumn('last_name', 'string', ['length' => 128, 'notnull' => false]);
		$table->addColumn('name_suffix', 'string', ['notnull' => false, 'length' => 64]);
		$table->addColumn('gender', 'string', ['notnull' => false, 'length' => 8]);
		$table->addColumn('birthday', 'date', ['notnull' => false]);
		$table->addColumn('description', 'text', ['notnull' => false]);
		$table->addColumn('skype', 'string', ['notnull' => false, 'length' => 255]);
		$table->addColumn('twitter', 'string', ['notnull' => false, 'length' => 255]);
		$table->addColumn('facebook', 'string', ['notnull' => false, 'length' => 255]);
		$table->addColumn('google_plus', 'string', ['notnull' => false, 'length' => 255]);
		$table->addColumn('linkedin', 'string', ['notnull' => false, 'length' => 255]);
		$table->addColumn('createdAt', 'datetime', []);
		$table->addColumn('updatedAt', 'datetime', ['notnull' => false]);
		$table->addColumn('email', 'string', ['notnull' => false, 'length' => 255]);

		//INDEXES
		$table->setPrimaryKey(['id']);
		$table->addIndex(['owner_id'], 'idx_contact_owner', []);
		$table->addIndex(['organization_id'], 'idx_contact_organization', []);
		$table->addIndex(['createdAt'], 'idx_contact_created_at', []);
		$table->addIndex(['updatedAt'], 'idx_contact_updated_at', []);
		$table->addIndex(['assigned_to'], 'idx_contact_assigned_to', []);
		$table->addIndex(['last_name', 'first_name'], 'idx_contact_name', []);
		$table->addIndex(['account_id'], 'idx_contact_account', []);
		$table->addIndex(['job_title'], 'idx_contact_job_title', []);

		//FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_organization'),
			['organization_id'],
			['id'],
			['onDelete' => 'SET NULL', 'onUpdate' => null],
			'fk_contact_organization'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_user'),
			['owner_id'],
			['id'],
			['onDelete' => 'SET NULL', 'onUpdate' => null],
			'fk_contact_owner'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_account'),
			['account_id'],
			['id'],
			['onDelete' => 'SET NULL', 'onUpdate' => null],
			'fk_contact_account'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_user'),
			['assigned_to'],
			['id'],
			['onDelete' => 'SET NULL', 'onUpdate' => null],
			'fk_contact_assignee'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_list_item'),
			['job_title'],
			['id'],
			['onDelete' => null, 'onUpdate' => null],
			'fk_contact_jobtitle'
		);
	}

	/**
	 * Create mekit_contact_address table
	 *
	 * @param Schema $schema
	 */
	protected function createMekitContactAddressTable(Schema $schema) {
		$table = $schema->createTable(self::$tableNameContactAddress);
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
		$table->addIndex(['owner_id'], 'idx_address_owner', []);
		$table->addIndex(['country_code'], 'idx_address_country_code', []);
		$table->addIndex(['region_code'], 'idx_address_region_code', []);

		//FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable(self::$tableNameContact),
			['owner_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null],
			'fk_contact_address_owner'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_dictionary_country'),
			['country_code'],
			['iso2_code'],
			['onDelete' => null, 'onUpdate' => null],
			'fk_contact_address_country'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_dictionary_region'),
			['region_code'],
			['combined_code'],
			['onDelete' => null, 'onUpdate' => null],
			'fk_contact_address_region'
		);
	}

	/**
	 * Create mekit_contact_adr_to_adr_type table
	 *
	 * @param Schema $schema
	 */
	protected function createMekitContactAdrToAdrTypeTable(Schema $schema) {
		$table = $schema->createTable(self::$tableNameContactAddressToType);
		$table->addColumn('contact_address_id', 'integer', []);
		$table->addColumn('type_name', 'string', ['length' => 16]);

		//INDEXES
		$table->setPrimaryKey(['contact_address_id', 'type_name']);
		$table->addIndex(['contact_address_id'], 'idx_addresstype_addressid', []);
		$table->addIndex(['type_name'], 'idx_addresstype_type_name', []);

		//FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable(self::$tableNameContactAddress),
			['contact_address_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null],
			'fk_contact_addresstype_addressid'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_address_type'),
			['type_name'],
			['name'],
			['onDelete' => null, 'onUpdate' => null],
			'fk_contact_addresstype_type_name'
		);
	}

	/**
	 * Create mekit_contact_email table
	 *
	 * @param Schema $schema
	 */
	protected function createMekitContactEmailTable(Schema $schema) {
		$table = $schema->createTable(self::$tableNameContactEmail);
		$table->addColumn('id', 'integer', ['autoincrement' => true]);
		$table->addColumn('account_id', 'integer', ['notnull' => false]);
		$table->addColumn('contact_id', 'integer', ['notnull' => false]);
		$table->addColumn('email', 'string', ['length' => 255]);
		$table->addColumn('is_primary', 'boolean', ['notnull' => false]);

		//INDEXES
		$table->setPrimaryKey(['id']);
		$table->addIndex(['email', 'is_primary'], 'primary_email_idx', []);
		$table->addIndex(['account_id'], 'idx_email_account', []);
		$table->addIndex(['contact_id'], 'idx_email_contact', []);

		//FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_account'),
			['account_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null],
			'fk_contact_email_account'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable(self::$tableNameContact),
			['contact_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null],
			'fk_contact_email_contact'
		);
	}

	/**
	 * Create mekit_contact_phone table
	 *
	 * @param Schema $schema
	 */
	protected function createMekitContactPhoneTable(Schema $schema) {
		$table = $schema->createTable(self::$tableNameContactPhone);
		$table->addColumn('id', 'integer', ['autoincrement' => true]);
		$table->addColumn('account_id', 'integer', ['notnull' => false]);
		$table->addColumn('contact_id', 'integer', ['notnull' => false]);
		$table->addColumn('phone', 'string', ['length' => 255]);
		$table->addColumn('is_primary', 'boolean', ['notnull' => false]);

		//INDEXES
		$table->setPrimaryKey(['id']);
		$table->addIndex(['phone', 'is_primary'], 'primary_phone_idx', []);
		$table->addIndex(['account_id'], 'idx_phone_account', []);
		$table->addIndex(['contact_id'], 'idx_phone_contact', []);

		//FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_account'),
			['account_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null],
			'fk_contact_phone_account'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable(self::$tableNameContact),
			['contact_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null],
			'fk_contact_phone_contact'
		);
	}
}