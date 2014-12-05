<?php
namespace Mekit\Bundle\TaskBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class MekitTaskBundle implements Migration {
	public static $tableNameTask = "mekit_task";

	/**
	 * @param Schema   $schema
	 * @param QueryBag $queries
	 */
	public function up(Schema $schema, QueryBag $queries) {
		/** Tables and foreign keys generation **/
		$this->createMekitTaskTable($schema);
	}

	/**
	 * Create mekit_task table
	 *
	 * @param Schema $schema
	 */
	protected function createMekitTaskTable(Schema $schema) {
		$table = $schema->createTable(self::$tableNameTask);
		$table->addColumn('id', 'integer', ['autoincrement' => true]);
		$table->addColumn('description', 'text', ['notnull' => false]);

		//INDEXES
		$table->setPrimaryKey(['id']);
	}
}