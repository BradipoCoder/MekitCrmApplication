<?php
namespace Mekit\Bundle\AccountBundle\Migrations\Schema\v1_1;

use Doctrine\DBAL\Schema\Schema;
use Mekit\Bundle\AccountBundle\Migrations\Schema\v1_0\MekitAccountBundle as MigrationBase;
use Oro\Bundle\ActivityBundle\Migration\Extension\ActivityExtension;
use Oro\Bundle\ActivityBundle\Migration\Extension\ActivityExtensionAwareInterface;
use Oro\Bundle\CommentBundle\Migration\Extension\CommentExtension;
use Oro\Bundle\CommentBundle\Migration\Extension\CommentExtensionAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * Class MekitAccountBundle
 */
class MekitAccountBundle implements Migration, CommentExtensionAwareInterface, ActivityExtensionAwareInterface
{
	/** @var CommentExtension */
	protected $commentExtension;

	/** @var  ActivityExtension */
	protected $activityExtension;

	/**
	 * @param CommentExtension $commentExtension
	 */
	public function setCommentExtension(CommentExtension $commentExtension) {
		$this->commentExtension = $commentExtension;
	}

	/**
	 * @param ActivityExtension $activityExtension
	 */
	public function setActivityExtension(ActivityExtension $activityExtension) {
		$this->activityExtension = $activityExtension;
	}

	/**
	 * @param Schema   $schema
	 * @param QueryBag $queries
	 */
	public function up(Schema $schema, QueryBag $queries) {
		self::addCommentAssociations($schema, $this->commentExtension);
		self::addActivityAssociations($schema, $this->activityExtension);
	}

	/**
	 * Enable comments for contacts
	 *
	 * @param Schema           $schema
	 * @param CommentExtension $commentExtension
	 */
	public static function addCommentAssociations(Schema $schema, CommentExtension $commentExtension) {
		$commentExtension->addCommentAssociation($schema, MigrationBase::$tableNameAccount);
	}

	/**
	 * Enable activities(email) for contacts
	 *
	 * @param Schema            $schema
	 * @param ActivityExtension $activityExtension
	 */
	public static function addActivityAssociations(Schema $schema, ActivityExtension $activityExtension) {
		$activityExtension->addActivityAssociation($schema, 'oro_email', MigrationBase::$tableNameAccount, true);
	}
}