<?php
namespace Mekit\Bundle\ListBundle\Form\Handler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

use Mekit\Bundle\ListBundle\Entity\ListItem;

/**
 * Class ListItemHandler
 */
class ListItemHandler {
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

	/**
	 *
	 * @param FormInterface $form
	 * @param Request       $request
	 * @param ObjectManager $manager
	 */
	public function __construct(FormInterface $form, Request $request, ObjectManager $manager) {
		$this->form = $form;
		$this->request = $request;
		$this->manager = $manager;
	}

	/**
	 * Process form
	 *
	 * @param  ListItem $entity
	 * @return bool True on successful processing, false otherwise
	 */
	public function process(ListItem $entity) {
		$this->form->setData($entity);
		if (in_array($this->request->getMethod(), array('POST', 'PUT'))) {
			$this->form->submit($this->request);
			if ($this->form->isValid()) {
				$this->setListItemName($entity);
				$this->onSuccess($entity);
				return true;
			}
		}
		return false;
	}

	/**
	 * Generates name for item from label - only if not a system defined element
	 * @param ListItem $entity
	 */
	protected function setListItemName(ListItem $entity) {
		if(!$entity->isSystem()) {
			$cleanLabel = str_replace(" ", "_", $entity->getLabel());
			$cleanLabel = preg_replace("/[^a-zA-Z0-9_]+/", "", $cleanLabel);
			$name = $entity->getListGroup()->getName() . "_" . strtoupper($cleanLabel);
			$entity->setName($name);
		}
	}

	/**
	 * "Success" form handler
	 * @param ListItem $entity
	 */
	protected function onSuccess(ListItem $entity) {
		$this->manager->persist($entity);
		$this->manager->flush();
	}
}