<?php
namespace Mekit\Bundle\RelationshipBundle\Migration\Extension;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\EntityExtendBundle\Migration\OroOptions;
use Oro\Bundle\EntityExtendBundle\Tools\ExtendHelper;

class RelationshipExtension implements ExtendExtensionAwareInterface {
	const REFERENCEABLE_TABLE_NAME = 'mekit_referenceable';

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
	 * @param string $referencedTableName - Target entity table name
	 * @param array $relationshipOptions
	 */
	public function addReferenceableElementRelationship(
		Schema $schema,
		$referencedTableName,
		$relationshipOptions = []
	) {
		//relationship cannot be disabled from UI
		$immutable = true;

		//@todo: check if target entity has all required relationship options set and if not bail out!
		/**
		 *              "referenceable"=true,
		 *              "label"="mekit.call.entity_plural_label",
		 *              "can_reference_itself"=false,
		 *              "datagrid_name_list"="calls-related-relationship",
		 *              "datagrid_name_select"="calls-related-select",
		 *              "autocomplete_search_columns"={"i2s"}
		 */

		//REFERENCEABLE
		$referenceableTable = $schema->getTable(self::REFERENCEABLE_TABLE_NAME);
		$referenceableClassName = $this->extendExtension->getEntityClassByTableName(self::REFERENCEABLE_TABLE_NAME);
		$referenceableTitleColumnNames = $referenceableTable->getPrimaryKeyColumns();
		$referenceableDetailedColumnNames = $referenceableTable->getPrimaryKeyColumns();
		$referenceableGridColumnNames = $referenceableTable->getPrimaryKeyColumns();

		//REFERENCED
		$referencedTable = $schema->getTable($referencedTableName);
		$referencedClassName = $this->extendExtension->getEntityClassByTableName($referencedTableName);


		//REFERENCED ENTITY OPTIONS
		$options = new OroOptions();
		$options->set('relationship', 'referenceable', true);
		$options->set('relationship', 'immutable', $immutable);
		$referencedTable->addOption(OroOptions::KEY, $options);


		//$associationName = ExtendHelper::buildAssociationName($referencedClassName);
		$associationName = Inflector::tableize(ExtendHelper::getShortClassName($referencedClassName));

		//CREATE THE 1:1 ASSOCIATION
		$this->extendExtension->addOneToOneRelation(
			$schema,
			$referencedTable,
			$associationName,
			$referenceableTable,
			$referenceableTitleColumnNames,
			$referenceableDetailedColumnNames,
			$referenceableGridColumnNames,
			$relationshipOptions
		);
	}
}
