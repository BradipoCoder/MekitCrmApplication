<?php
namespace Mekit\Bundle\CallBundle\Migrations\Schema\v1_1;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Mekit\Bundle\CallBundle\Migrations\Schema\v1_0\MekitCallBundle as MigrationBase;

use Mekit\Bundle\RelationshipBundle\Migration\Extension\RelationshipExtension;
use Mekit\Bundle\RelationshipBundle\Migration\Extension\RelationshipExtensionAwareInterface;

/**
 * usage: app/console oro:migration:load --show-queries --bundles MekitCallBundle --dry-run
 *
 * Class MekitCallBundle
 */
class MekitCallBundle implements Migration, RelationshipExtensionAwareInterface {
	/**
	 * @var RelationshipExtension
	 */
	protected $relationshipExtension;

	/**
	 * @param RelationshipExtension $relationshipExtension
	 */
	public function setRelationshipExtension(RelationshipExtension $relationshipExtension) {
		$this->relationshipExtension = $relationshipExtension;
	}

	/**
	 * @param Schema $schema
	 * @param QueryBag $queries
	 */
	public function up(Schema $schema, QueryBag $queries) {
		self::addRelationship($schema, $this->relationshipExtension);
	}

	/**
	 * Enable notes for accounts
	 *
	 * @param Schema        $schema
	 * @param RelationshipExtension $relationshipExtension
	 */
	public static function addRelationship(Schema $schema, RelationshipExtension $relationshipExtension) {
		$relationshipExtension->addReferenceableElementRelationship($schema, MigrationBase::$tableNameCall);
	}
}