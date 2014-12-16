<?php

namespace Mekit\Bundle\ContactBundle\Form\Handler;

use Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

use Mekit\Bundle\AccountBundle\Entity\Account;
use Mekit\Bundle\ContactBundle\Entity\Contact;

use Oro\Bundle\TagBundle\Entity\TagManager;
use Oro\Bundle\TagBundle\Form\Handler\TagHandlerInterface;

/**
 * Class ContactHandler
 */
class ContactHandler implements TagHandlerInterface {
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
	 * @var TagManager
	 */
	protected $tagManager;

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
	 * @param  Contact $entity
	 * @return bool True on successful processing, false otherwise
	 */
	public function process(Contact $entity) {
		$this->form->setData($entity);
		if (in_array($this->request->getMethod(), array('POST', 'PUT'))) {
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
	 * @param Contact $entity
	 */
	protected function onSuccess(Contact $entity) {
		$this->doReferenceableStuff($entity);
		$this->manager->persist($entity);
		$this->manager->flush();
		$this->tagManager->saveTagging($entity);
	}

	/**
	 * @param Contact $entity
	 */
	public function doReferenceableStuff($entity) {
		if(!$entity->getReferenceableElement()) {
			$className = $this->manager->getClassMetadata(get_class($entity))->getName();
			$ref = new ReferenceableElement($className);
			$entity->setReferenceableElement($ref);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function setTagManager(TagManager $tagManager) {
		$this->tagManager = $tagManager;
	}
}