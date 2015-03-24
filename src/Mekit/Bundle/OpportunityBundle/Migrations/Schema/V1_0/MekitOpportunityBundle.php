<?php
namespace Mekit\Bundle\OpportunityBundle\Migrations\Schema\V1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class MekitOpportunityBundle implements Migration
{
	public static $tableNameOpportunity = "mekit_opportunity";

	/**
	 * @param Schema   $schema
	 * @param QueryBag $queries
	 */
	public function up(Schema $schema, QueryBag $queries) {
		/** Tables and foreign keys generation **/
		$this->createMekitOpportunityTable($schema);
	}

	/**
	 * Create mekit_project table
	 *
	 * @param Schema $schema
	 */
	protected function createMekitOpportunityTable(Schema $schema) {
		$table = $schema->createTable(self::$tableNameOpportunity);
		$table->addColumn('id', 'integer', ['autoincrement' => true]);
		$table->addColumn('name', 'string', ['length' => 255, 'notnull' => true]);
		$table->addColumn('description', 'text', ['notnull' => false]);
		$table->addColumn('amount', 'float', ['notnull' => false]);
		$table->addColumn('probability', 'float', ['notnull' => false]);
		$table->addColumn('state', 'integer', ['notnull' => false]);
		$table->addColumn('account_id', 'integer', ['notnull' => false]);
		$table->addColumn('organization_id', 'integer', ['notnull' => false]);
		$table->addColumn('owner_id', 'integer', ['notnull' => false]);
		$table->addColumn('createdAt', 'datetime', []);
		$table->addColumn('updatedAt', 'datetime', ['notnull' => false]);
		$table->addColumn('workflowItem_id', 'integer', ['notnull' => false]);
		$table->addColumn('workflowStep_id', 'integer', ['notnull' => false]);

		//INDEXES
		$table->setPrimaryKey(['id']);
		$table->addIndex(['owner_id'], 'idx_opportunity_owner', []);
		$table->addIndex(['organization_id'], 'idx_opportunity_organization', []);
		$table->addIndex(['createdAt'], 'idx_opportunity_created_at', []);
		$table->addIndex(['updatedAt'], 'idx_opportunity_updated_at', []);
		$table->addIndex(['name'], 'idx_opportunity_name', []);
		$table->addIndex(['state'], 'idx_opportunity_state', []);
		$table->addIndex(['account_id'], 'idx_opportunity_account', []);

		//FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_list_item'),
			['state'],
			['id'],
			['onDelete' => null],
			'fk_opportunity_state'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_account'),
			['account_id'],
			['id'],
			['onDelete' => 'SET NULL'],
			'fk_opportunity_account'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_organization'),
			['organization_id'],
			['id'],
			['onDelete' => 'SET NULL'],
			'fk_opportunity_organization'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_user'),
			['owner_id'],
			['id'],
			['onDelete' => 'SET NULL'],
			'fk_opportunity_owner'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_workflow_item'),
			['workflowItem_id'],
			['id'],
			['onDelete' => 'SET NULL'],
			'fk_opportunity_wf_item'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('oro_workflow_step'),
			['workflowStep_id'],
			['id'],
			['onDelete' => 'SET NULL'],
			'fk_opportunity_wf_step'
		);
	}

}