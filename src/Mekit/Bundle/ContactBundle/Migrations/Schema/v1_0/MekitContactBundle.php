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

	/**
	 * @param Schema   $schema
	 * @param QueryBag $queries
	 */
	public function up(Schema $schema, QueryBag $queries) {
		/** Tables and foreign keys generation **/
		$this->createMekitContactTable($schema);
	}

	/**
	 * Create mekit_contact table
	 *
	 * @param Schema $schema
	 */
	protected function createMekitContactTable(Schema $schema) {
		$table = $schema->createTable(self::$tableNameContact);
		$table->addColumn('id', 'integer', ['autoincrement' => true]);
		$table->addColumn('job_title', 'integer', ['notnull' => false]);
		$table->addColumn('organization_id', 'integer', ['notnull' => false]);
		$table->addColumn('owner_id', 'integer', ['notnull' => false]);
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
		$table->addIndex(['last_name', 'first_name'], 'idx_contact_name', []);
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
			$schema->getTable('mekit_list_item'),
			['job_title'],
			['id'],
			['onDelete' => null, 'onUpdate' => null],
			'fk_contact_jobtitle'
		);
	}
}