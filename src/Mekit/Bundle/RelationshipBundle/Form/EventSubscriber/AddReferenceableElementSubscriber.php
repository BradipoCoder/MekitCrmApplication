<?php
namespace Mekit\Bundle\RelationshipBundle\Form\EventSubscriber;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AddReferenceableElementSubscriber implements EventSubscriberInterface {

	/**
	 * {@inheritdoc}
	 */
	public static function getSubscribedEvents() {
		return [
			FormEvents::PRE_SET_DATA => 'preSetData',
			//FormEvents::POST_SUBMIT  => 'postSubmit'
		];
	}

	/**
	 * Add "mekit_referenceable_element" field type to form
	 *
	 * @param FormEvent $event
	 */
	public function preSetData(FormEvent $event) {
		/** @var $entity - Any entity */
		$entity = $event->getData();
		$form = $event->getForm();
		$isNewItem = (!is_object($entity) || null === $entity->getId());

		//@todo: for some reason this event gets called twice once without data and once with data - check this
		// this is why we need to remove previously added form field if $entity is NOT new
		if ($isNewItem) {
			$form->add('referenceableElement', 'mekit_referenceable_element',
				[
					'label' => false,
					'attr'=> ['class'=>'hide']
				]
			);
		} else {
			if ($form->has('referenceableElement')) {
				$form->remove('referenceableElement');
			}
		}
	}

	/**
	 *
	 * @param FormEvent $event
	 */
	public function postSubmit(FormEvent $event) {
		//?
	}

}