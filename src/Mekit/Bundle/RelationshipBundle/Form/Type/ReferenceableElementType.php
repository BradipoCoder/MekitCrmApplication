<?php
/**
 * Created by Adam Jakab.
 * Date: 23/01/15
 * Time: 16.53
 */

namespace Mekit\Bundle\RelationshipBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReferenceableElementType extends AbstractType {
	/**
	 *
	 */
	public function __construct() {}

	/**
	 * @param FormBuilderInterface $builder
	 * @param array                $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {

		/*
		$builder->addEventListener(
			FormEvents::POST_SET_DATA,
			function (FormEvent $event) {
				if($event->getData()) {
					/** @var Account $account * /
					$account = $event->getData();
					$references = $account->getReferenceableElement()->getReferences();

					$form = $event->getForm();

					$data = [];

					/** @var ReferenceableElement $reference * /
					foreach($references as $reference) {
						$baseEntity = $reference->getBaseEntity();
						$baseEntityClass = get_class($baseEntity);
						echo $baseEntityClass;
						$data[$baseEntityClass][] = $baseEntity;
					}
					//$form->get("extra_field_1")->setData($data['Mekit\Bundle\AccountBundle\Entity\Account']);
					//$form->get("extra_field_2")->setData($data['Mekit\Bundle\ContactBundle\Entity\Contact']);
				}
			}
		);*/

		$builder->add('extra_field_1', 'referenceable_element_multi_select2', [
			'mapped' => false,
			'required' => false,
			'label' => 'Referenced Accounts',
			'configs' => [
				'entity_name' => 'Mekit\Bundle\AccountBundle\Entity\Account',/*The entity type we want to select*/
				'entity_fields' => ['name', 'vatid'],
				'entity_id' => 0
			]
		]);

		$builder->add('extra_field_2', 'referenceable_element_multi_select2', [
			'mapped' => false,
			'required' => false,
			'label' => 'Referenced Contacts',
			'configs' => [
				'entity_name' => 'Mekit\Bundle\ContactBundle\Entity\Contact',/*The entity type we want to select*/
				'entity_fields' => ['firstName', 'lastName'],
				'entity_id' => 0
			]
		]);


	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
			array(
				'data_class' => 'Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement',
			)
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName() {
		return 'mekit_referenceable_element';
	}
}