<?php
namespace Mekit\Bundle\ListBundle\Helper;

use Doctrine\ORM\EntityRepository;
use Mekit\Bundle\ListBundle\Entity\ListGroup;
use Mekit\Bundle\ListBundle\Entity\Repository\ListItemRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 *
 * TEMPORARY SOLUTION!
 * This is in absence of a proper interface and form type for list group selectors
 * TEMPORARY SOLUTION!
 *
 * todo: get rid of this an build complete solution (like tagging)
 * Class FormHelper
 */
class FormHelper {
	/**
	 * @var ObjectManager
	 */
	protected $manager;


	public function __construct(ObjectManager $manager) {
		$this->manager = $manager;
	}

	/**
	 * @param FormBuilderInterface $builder
	 * @param string               $propertyName
	 * @param string               $listGroupName
	 * @param string               $label
	 */
	public function addListSelectorToFormBuilder(FormBuilderInterface $builder, $propertyName, $listGroupName, $label) {
		/** @var EntityRepository $listGroupRepo */
		$listGroupRepo = $this->manager->getRepository('MekitListBundle:ListGroup');
		/** @var ListItemRepository $listItemRepo */
		$listItemRepo = $this->manager->getRepository('MekitListBundle:ListItem');

		/** @var ListGroup $listGroup */
		$listGroup = $listGroupRepo->findOneBy(["name" => $listGroupName]);

		$constraints = [];
		if($listGroup->isRequired()) {
			$constraints = [
				new NotBlank(["message" => "Select an item from the list"])
			];
		}

		$builder->add(
			$propertyName,
			'entity',
			array(
				'required'    => $listGroup->isRequired(),
				'label'       => $label,
				'class'       => 'MekitListBundle:ListItem',
				'empty_value' => $listGroup->getEmptyValue(),
				'query_builder' => $listItemRepo->getListItemQueryBuilder($listGroupName),
				'constraints'   => $constraints
			)
		);


	}
}