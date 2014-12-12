<?php
namespace Mekit\Bundle\AccountBundle\Migrations\Schema\v1_2;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Mekit\Bundle\AccountBundle\Migrations\Schema\v1_0\MekitAccountBundle as MigrationBase;

use Oro\Bundle\ActivityBundle\Migration\Extension\ActivityExtension;
use Oro\Bundle\ActivityBundle\Migration\Extension\ActivityExtensionAwareInterface;

/**
 * Class MekitAccountBundle
 */
class MekitAccountBundle implements Migration, ActivityExtensionAwareInterface {
	/**
	 * @var ActivityExtension
	 */
	protected $activityExtension;

	/**
	 * @param ActivityExtension $activityExtension
	 */
	public function setActivityExtension(ActivityExtension $activityExtension) {
		$this->activityExtension = $activityExtension;
	}

	/**
	 * @param Schema $schema
	 * @param QueryBag $queries
	 */
	public function up(Schema $schema, QueryBag $queries) {
		self::addActivityAssociations($schema, $this->activityExtension);
	}

	/**
	 * Enable activities for accounts
	 *
	 * @param Schema        $schema
	 * @param ActivityExtension $activityExtension
	 */
	public static function addActivityAssociations(Schema $schema, ActivityExtension $activityExtension) {
		$activityExtension->addActivityAssociation($schema, 'oro_email', MigrationBase::$tableNameAccount);
	}
}