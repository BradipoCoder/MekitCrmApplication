<?php
namespace Mekit\Bundle\ProjectBundle\Migrations\Schema\v1_1;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Mekit\Bundle\ProjectBundle\Migrations\Schema\v1_0\MekitProjectBundle as MigrationBase;

use Oro\Bundle\NoteBundle\Migration\Extension\NoteExtension;
use Oro\Bundle\NoteBundle\Migration\Extension\NoteExtensionAwareInterface;

/**
 * Class MekitProjectBundle
 */
class MekitProjectBundle implements Migration, NoteExtensionAwareInterface {
	/**
	 * @var NoteExtension
	 */
	protected $noteExtension;

	/**
	 * @param NoteExtension $noteExtension
	 */
	public function setNoteExtension(NoteExtension $noteExtension) {
		$this->noteExtension = $noteExtension;
	}

	/**
	 * @param Schema $schema
	 * @param QueryBag $queries
	 */
	public function up(Schema $schema, QueryBag $queries) {
		self::addNoteAssociations($schema, $this->noteExtension);
	}

	/**
	 * Enable notes for accounts
	 *
	 * @param Schema        $schema
	 * @param NoteExtension $noteExtension
	 */
	public static function addNoteAssociations(Schema $schema, NoteExtension $noteExtension) {
		$noteExtension->addNoteAssociation($schema, MigrationBase::$tableNameProject);
	}
}