<?php
namespace Mekit\Bundle\AccountBundle\Migrations\Schema\v1_1;

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
class MekitAccountBundle implements Migration, NoteExtensionAwareInterface {
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
		$noteExtension->addNoteAssociation($schema, MigrationBase::$tableNameAccount);
	}
}