<?php
namespace Mekit\Bundle\RelationshipBundle\Migration\Extension;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtension;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtensionAwareInterface;
use Oro\Bundle\EntityExtendBundle\Migration\OroOptions;
use Oro\Bundle\EntityExtendBundle\Tools\ExtendHelper;

class RelationshipExtension implements ExtendExtensionAwareInterface {
	/** @var ExtendExtension */
	protected $extendExtension;

	/**
	 * {@inheritdoc}
	 */
	public function setExtendExtension(ExtendExtension $extendExtension) {
		$this->extendExtension = $extendExtension;
	}

	/**
	 * Adds the association between the target table and the source table
	 *
	 * @param Schema $schema
	 * @param string $sourceTableName - Source entity table name. It is owning side of the association
	 * @param string $targetTableName - Target entity table name
	 * @param array $targetTitleColumnNames
	 * @param array $targetDetailedColumnNames
	 * @param array $targetGridColumnNames
	 * @param array $relationshipOptions
	 * @param bool  $immutable       - Set TRUE to prohibit disabling the relationship from UI
	 */
	public function addRelationship(
		Schema $schema,
		$sourceTableName,
		$targetTableName,
		$targetTitleColumnNames = null,
		$targetDetailedColumnNames = null,
		$targetGridColumnNames = null,
		$relationshipOptions = null,
		$immutable = false
	) {

		die("UNUSABLE!!! Missing addOneToOneRelation method on ExtendExtension");

		/*
		//SOURCE
		$sourceTable = $schema->getTable($sourceTableName);
		$sourceClassName = $this->extendExtension->getEntityClassByTableName($sourceTableName);

		//TARGET
		$targetTable = $schema->getTable($targetTableName);
		$targetClassName = $this->extendExtension->getEntityClassByTableName($targetTableName);
		if (!$targetTitleColumnNames) {
			// Column names used to show the title of the target entity
			$targetTitleColumnNames = $targetTable->getPrimaryKeyColumns();
		}
		if (!$targetDetailedColumnNames) {
			// Column names used to show detailed information about the target entity
			$targetDetailedColumnNames = $targetTable->getPrimaryKeyColumns();
		}
		if (!$targetGridColumnNames) {
			// Column names used to show target entity in a grid
			$targetGridColumnNames = $targetTable->getPrimaryKeyColumns();
		}

		//TARGET OPTIONS
		//@todo: make options for this
		//@todo: do we need to do the same for source table?
		if(true) {
			$options = new OroOptions();
			$options->set('relationship', 'enabled', true);
			$options->append(
				'relationship',
				'relationships',
				$sourceClassName
			);
			if ($immutable) {
				$options->append(
					'relationship',
					'immutable',
					$sourceClassName
				);
			}
			$targetTable->addOption(OroOptions::KEY, $options);
		}

		$associationName = ExtendHelper::buildAssociationName($targetClassName, 'relationship_'.$sourceClassName);

		//ASSOCIATION OPTIONS
		if (!$relationshipOptions) {
			$relationshipOptions = [];
		}
		$relationshipOptions['extend']['without_default'] = true;
		*/

		//CREATE THE ASSOCIATION
		/*
		$this->extendExtension->addManyToManyRelation(
			$schema,
			$sourceTable,
			$associationName,
			$targetTable,
			$targetTitleColumnNames,
			$targetDetailedColumnNames,
			$targetGridColumnNames,
			$relationshipOptions,
			'manyToMany'
		);*/

	}
}
