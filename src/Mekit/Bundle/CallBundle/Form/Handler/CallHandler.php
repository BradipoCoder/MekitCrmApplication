<?php

namespace Mekit\Bundle\CallBundle\Form\Handler;


use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

use Mekit\Bundle\EventBundle\Entity\Event;
use Mekit\Bundle\CallBundle\Entity\Call;

/**
 * Class CallHandler
 */
class CallHandler {
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
	 * @param  Call $entity
	 * @return bool True on successful processing, false otherwise
	 */
	public function process(Call $entity) {
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
	 * @param Call $entity
	 */
	protected function onSuccess(Call $entity) {
		$this->manager->persist($entity);
		$this->manager->flush();
	}
}