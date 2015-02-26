<?php
namespace Mekit\Bundle\ProjectBundle\Form\Handler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

use Mekit\Bundle\ProjectBundle\Entity\Project;

use Oro\Bundle\TagBundle\Entity\TagManager;
use Oro\Bundle\TagBundle\Form\Handler\TagHandlerInterface;

class ProjectHandler implements TagHandlerInterface {
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
	 * @param  Project $entity
	 * @return bool True on successful processing, false otherwise
	 */
	public function process(Project $entity) {
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
	 * @param Project $entity
	 */
	protected function onSuccess(Project $entity) {
		$this->manager->persist($entity);
		$this->manager->flush();
		$this->tagManager->saveTagging($entity);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setTagManager(TagManager $tagManager) {
		$this->tagManager = $tagManager;
	}
}