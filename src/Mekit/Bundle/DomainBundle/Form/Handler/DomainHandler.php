<?php

namespace Mekit\Bundle\DomainBundle\Form\Handler;

use Mekit\Bundle\DomainBundle\Entity\Domain;
use Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;


/**
 * Class DomainHandler
 */
class DomainHandler {
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
	 * @param  Domain $entity
	 * @return bool True on successful processing, false otherwise
	 */
	public function process(Domain $entity) {
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
	 * @param Domain $entity
	 */
	protected function onSuccess(Domain $entity) {
		$this->manager->persist($entity);
		$this->manager->flush();
	}


}