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






//	public function addListSelectorToFormBuilder(FormBuilderInterface $builder, $propertyName, $listGroupName, $label) {
//		/** @var EntityRepository $listGroupRepo */
//		$listGroupRepo = $this->manager->getRepository('MekitListBundle:ListGroup');
//		/** @var ListItemRepository $listItemRepo */
//		$listItemRepo = $this->manager->getRepository('MekitListBundle:ListItem');
//
//		/** @var ListGroup $listGroup */
//		$listGroup = $listGroupRepo->findOneBy(["name" => $listGroupName]);
//
//		$constraints = [];
//		if($listGroup->isRequired()) {
//			$constraints = [
//				new NotBlank(["message" => "Select an item from the list"])
//			];
//		}
//
//		$builder->add(
//			$propertyName,
//			'entity',
//			array(
//				'required'    => $listGroup->isRequired(),
//				'label'       => $label,
//				'class'       => 'MekitListBundle:ListItem',
//				'empty_value' => $listGroup->getEmptyValue(),
//				'query_builder' => $listItemRepo->getListItemQueryBuilder($listGroupName),
//				'constraints'   => $constraints
//			)
//		);
//
//
//	}
}