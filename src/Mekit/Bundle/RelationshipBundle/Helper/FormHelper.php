<?php
namespace Mekit\Bundle\RelationshipBundle\Helper;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement;
use Mekit\Bundle\RelationshipBundle\Entity\Repository\ReferenceableElementRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class FormHelper
 */
class FormHelper {
	/**
	 * @var ObjectManager
	 */
	protected $manager;

	/** @var ReferenceableElementRepository */
	protected $refElRepo;


	public function __construct(ObjectManager $manager) {
		$this->manager = $manager;
		$this->refElRepo = $this->manager->getRepository("MekitRelationshipBundle:ReferenceableElement");
	}

	/**
	 * @param string $type
	 * @return QueryBuilder
	 */
	public function getReferencedElementsQueryBuilderByType($type) {
		return($this->refElRepo->getReferencedElementsQueryBuilderByType($type));
	}

}