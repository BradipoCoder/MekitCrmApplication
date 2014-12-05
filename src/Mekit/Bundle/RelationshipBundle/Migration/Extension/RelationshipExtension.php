<?php
namespace Mekit\Bundle\RelationshipBundle\Migration\Extension;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtension;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtensionAwareInterface;
use Oro\Bundle\EntityExtendBundle\Migration\OroOptions;
use Oro\Bundle\EntityExtendBundle\Tools\ExtendHelper;

/**
 * Class RelationshipExtension
 */
class RelationshipExtension implements ExtendExtensionAwareInterface {
	const RELATIONSHIP_TABLE_NAME = 'mekit_relationship';

	/** @var ExtendExtension */
	protected $extendExtension;

	/**
	 * {@inheritdoc}
	 */
	public function setExtendExtension(ExtendExtension $extendExtension) {
		$this->extendExtension = $extendExtension;
	}

	/**
	 * Adds the associations as both source and target between the target table and the relationship table
	 *
	 * @param Schema $schema
	 * @param string $targetTableName Target entity table name
	 * @param string $targetColumnName A column name is used to show related entity
	 */
	public function addNoteAssociation(
		Schema $schema,
		$targetTableName,
		$targetColumnName = null
	) {
		$relationshipTable = $schema->getTable(self::RELATIONSHIP_TABLE_NAME);
		$targetTable = $schema->getTable($targetTableName);

		if (empty($targetColumnName)) {
			$primaryKeyColumns = $targetTable->getPrimaryKeyColumns();
			$targetColumnName = array_shift($primaryKeyColumns);
		}

		$options = new OroOptions();
		$options->set('relationship', 'enabled', true);
		$targetTable->addOption(OroOptions::KEY, $options);

		//SOURCE
		$sourceAssociationName = ExtendHelper::buildAssociationName(
			$this->extendExtension->getEntityClassByTableName($targetTableName), 'rel_source'
		);

		$this->extendExtension->addManyToOneRelation(
			$schema,
			$relationshipTable,
			$sourceAssociationName,
			$targetTable,
			$targetColumnName
		);

		//TARGET
		$targetAssociationName = ExtendHelper::buildAssociationName(
			$this->extendExtension->getEntityClassByTableName($targetTableName), 'rel_target'
		);

		$this->extendExtension->addManyToOneRelation(
			$schema,
			$relationshipTable,
			$targetAssociationName,
			$targetTable,
			$targetColumnName
		);

	}
}