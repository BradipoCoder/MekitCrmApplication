<?php
namespace Mekit\Bundle\ListBundle\Helper;

use Doctrine\ORM\EntityRepository;
use Mekit\Bundle\ListBundle\Entity\ListGroup;
use Mekit\Bundle\ListBundle\Entity\Repository\ListItemRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Doctrine\ORM\EntityManager;
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
	 * @var EntityManager
	 */
	protected $em;


	public function __construct(Doctrine $doctrine) {
		$this->em = $doctrine->getManager();
	}

	/**
	 * @param FormBuilderInterface $builder
	 * @param string               $propertyName
	 * @param string               $listGroupName
	 * @param string               $label
	 */
	public function addListSelectorToFormBuilder(FormBuilderInterface $builder, $propertyName, $listGroupName, $label) {
		/** @var EntityRepository $listGroupRepo */
		$listGroupRepo = $this->em->getRepository('MekitListBundle:ListGroup');
		/** @var ListItemRepository $listItemRepo */
		$listItemRepo = $this->em->getRepository('MekitListBundle:ListItem');

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