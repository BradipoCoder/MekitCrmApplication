<?php
namespace Mekit\Bundle\ProjectBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class MekitProjectBundle implements Migration {
	public static $tableNameProject = "mekit_project";

	/**
	 * @param Schema   $schema
	 * @param QueryBag $queries
	 */
	public function up(Schema $schema, QueryBag $queries) {
		/** Tables and foreign keys generation **/
		$this->createMekitProjectTable($schema);
	}

	/**
	 * Create mekit_project table
	 *
	 * @param Schema $schema
	 */
	protected function createMekitProjectTable(Schema $schema) {
		$table = $schema->createTable(self::$tableNameProject);
		$table->addColumn('id', 'integer', ['autoincrement' => true]);
		$table->addColumn('name', 'string', ['length' => 255]);
		$table->addColumn('description', 'text', ['notnull' => false]);
		$table->addColumn('organization_id', 'integer', ['notnull' => false]);
		$table->addColumn('owner_id', 'integer', ['notnull' => false]);
		$table->addColumn('createdAt', 'datetime', []);
		$table->addColumn('updatedAt', 'datetime', ['notnull' => false]);

		//INDEXES
		$table->setPrimaryKey(['id']);
		$table->addIndex(['owner_id'], 'idx_project_owner', []);
		$table->addIndex(['organization_id'], 'idx_project_organization', []);
		$table->addIndex(['createdAt'], 'idx_project_created_at', []);
		$table->addIndex(['updatedAt'], 'idx_project_updated_at', []);
		$table->addIndex(['name'], 'idx_project_name', []);

		//FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_organization'),
			['organization_id'],
			['id'],
			['onDelete' => 'SET NULL', 'onUpdate' => null],
			'fk_project_organization'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_user'),
			['owner_id'],
			['id'],
			['onDelete' => 'SET NULL', 'onUpdate' => null],
			'fk_project_owner'
		);
	}

}