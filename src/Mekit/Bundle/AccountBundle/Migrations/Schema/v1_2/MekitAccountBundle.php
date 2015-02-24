<?php
namespace Mekit\Bundle\AccountBundle\Migrations\Schema\v1_2;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Mekit\Bundle\AccountBundle\Migrations\Schema\v1_0\MekitAccountBundle as MigrationBase;

use Oro\Bundle\NoteBundle\Migration\Extension\NoteExtension;
use Oro\Bundle\NoteBundle\Migration\Extension\NoteExtensionAwareInterface;

/**
 * usage: app/console oro:migration:load --show-queries --bundles MekitAccountBundle --dry-run
 *
 * Class MekitAccountBundle
 */
class MekitAccountBundle implements Migration {
	/**
	 * @param Schema $schema
	 * @param QueryBag $queries
	 */
	public function up(Schema $schema, QueryBag $queries) {
		$this->create_contact_relations($schema);
	}

	protected function create_contact_relations(Schema $schema) {
		$relationTableName = "mekit_rel_account_contact";
		$table = $schema->createTable($relationTableName);
		$table->addColumn('account_id', 'integer', ['notnull' => true]);
		$table->addColumn('contact_id', 'integer', ['notnull' => true]);
		// INDEXES
		$table->setPrimaryKey(['account_id', 'contact_id']);
		$table->addIndex(['account_id'], 'idx_account', []);
		$table->addIndex(['contact_id'], 'idx_contact', []);
		// FOREIGN KEYS
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_account'),
			['account_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null],
			'fk_account'
		);
		$table->addForeignKeyConstraint(
			$schema->getTable('mekit_contact'),
			['contact_id'],
			['id'],
			['onDelete' => 'CASCADE', 'onUpdate' => null],
			'fk_contact'
		);
	}

}