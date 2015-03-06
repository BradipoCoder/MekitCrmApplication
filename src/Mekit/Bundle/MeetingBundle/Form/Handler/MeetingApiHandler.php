<?php
namespace Mekit\Bundle\MeetingBundle\Form\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Mekit\Bundle\CrmBundle\Relationship\RelationshipManager;
use Mekit\Bundle\MeetingBundle\Entity\Meeting;
use Oro\Bundle\EntityBundle\Tools\EntityRoutingHelper;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MeetingApiHandler
 */
class MeetingApiHandler
{
	/**
	 * @var FormInterface
	 */
	protected $form;

	/**
	 * @var Request
	 */
	protected $request;

	/**
	 * @var ObjectManager
	 */
	protected $manager;

	/** @var EntityRoutingHelper */
	protected $entityRoutingHelper;

	/** @var RelationshipManager */
	protected $relationshipManager;


	/**
	 *
	 * @param FormInterface       $form
	 * @param Request             $request
	 * @param ObjectManager       $manager
	 * @param EntityRoutingHelper $entityRoutingHelper
	 * @param RelationshipManager $relationshipManager
	 */
	public function __construct(FormInterface $form, Request $request, ObjectManager $manager,
	                            EntityRoutingHelper $entityRoutingHelper, RelationshipManager $relationshipManager) {
		$this->form = $form;
		$this->request = $request;
		$this->manager = $manager;
		$this->entityRoutingHelper = $entityRoutingHelper;
		$this->relationshipManager = $relationshipManager;
	}

	/**
	 * Process form
	 *
	 * @param  Meeting $entity
	 * @return bool True on successful processing, false otherwise
	 */
	public function process(Meeting $entity) {
		$action = $this->entityRoutingHelper->getAction($this->request);
		$relatedEntityClass = $this->entityRoutingHelper->getEntityClassName($this->request);
		$relatedEntityId = $this->entityRoutingHelper->getEntityId($this->request);

		if ($relatedEntityClass
		    && $relatedEntityId
		    && !$entity->getId()
		    && $this->request->getMethod() === 'GET'
		    && $action === 'assign'
		) {
			$this->relationshipManager->autoAssignRelatedEntity($entity, $relatedEntityClass, $relatedEntityId);
		}

		$this->form->setData($entity);
		if (in_array(
			$this->request->getMethod(), array(
			'POST',
			'PUT'
		)
		)) {
			$this->form->submit($this->request);
			if ($this->form->isValid()) {
				$this->onSuccess($entity);

				return true;
			}
		}

		return false;
	}

	/**
	 * "Success" form handler
	 *
	 * @param Meeting $entity
	 */
	protected function onSuccess(Meeting $entity) {
		$this->manager->persist($entity);
		$this->manager->flush();
	}

	/**
	 * @return FormInterface
	 */
	public function getForm() {
		return $this->form;
	}
}