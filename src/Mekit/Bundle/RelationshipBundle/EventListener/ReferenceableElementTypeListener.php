<?php
namespace Mekit\Bundle\RelationshipBundle\EventListener;

use Mekit\Bundle\RelationshipBundle\Entity\Manager\ReferenceManager;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Mekit\Bundle\RelationshipBundle\Entity\Referenceable;/*this is the interface all entities must have if they are referenceable*/
use Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement;
use Symfony\Component\Form\FormInterface;

class ReferenceableElementTypeListener implements EventSubscriberInterface {
	/** @var  ReferenceManager */
	protected $referenceManager;

	/**
	 * @param ReferenceManager $referenceManager
	 */
	public function __construct(ReferenceManager $referenceManager) {
		$this->referenceManager = $referenceManager;
	}

	public static function getSubscribedEvents() {
		return [
			FormEvents::PRE_SET_DATA => 'preSetData',
			FormEvents::POST_SET_DATA => 'postSetData'
		];
	}

	/**
	 * @param FormEvent $event
	 */
	public function preSetData(FormEvent $event) {
		if($event->getData()) {
			$this->createFields($event->getForm());
		}
	}

	/**
	 * The referenceableElementType form to which we are adding this subscriber has the ReferenceableElement entity
	 * as underlying entity bound to the form. This ReferenceableElement is that of the owning entity we are editing.
	 * The relationship to the owning entity is OneToOne and we can get it with ReferenceableElement::getBaseEntity or
	 * with Entity specific (getAccount, getContact, etc.) getters.
	 *
	 * @param FormEvent $event
	 */
	public function postSetData(FormEvent $event) {
		/** @var ReferenceableElement $entity */
		if(($entity = $event->getData())) {
			if(!$entity instanceof ReferenceableElement) {
				throw new UnexpectedTypeException($entity, 'Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement');
			}
			$this->populateFields($event->getForm(), $this->getReferencesDataArray($entity));
		}
	}

	/**
	 * Creates all reference fields dynamically for all referenceable entities
	 *
	 * @param FormInterface $form
	 */
	protected function createFields(FormInterface $form) {
		$referenceableConfigs = $this->referenceManager->getReferenceableEntityConfigurations();
		foreach($referenceableConfigs as $config) {
			$entityName = $config->getId()->getClassName();
			$entityLabel = $config->get("label", false, $entityName);
			$entitySearchColumns = $config->get("autocomplete_search_columns", false, []);
			$fieldName = str_replace('\\', '_', $entityName);
			$fieldOptions = [
				'mapped' => false,
				'required' => false,
				'label' => $entityLabel,
				'configs' => [
					'entity_name' => $entityName, /* The entity type we want to select */
					'entity_fields' => $entitySearchColumns,
					'entity_id' => 0 /* @todo: this is unused and can be removed */
				]
			];
			$form->add($fieldName, 'referenceable_element_multi_select2', $fieldOptions);
			echo '<br />' . $entityName . " - " . json_encode($entitySearchColumns);
		}
	}

	/**
	 * Populates fields with data
	 *
	 * @param FormInterface $form
	 * @param array         $data
	 */
	protected function populateFields(FormInterface $form, Array $data) {
		$fields = $form->all();
		foreach($fields as $field) {
			$fieldConfigsArray = $field->getConfig()->getOption("configs", []);
			$fieldEntityName =  (isset($fieldConfigsArray["entity_name"]) ? $fieldConfigsArray["entity_name"] : false);
			if($fieldEntityName) {
				//echo '<br />' . $field->getName() . ": " . $fieldEntityName;
				if(array_key_exists($fieldEntityName, $data)) {
					$field->setData($data[$fieldEntityName]);
				}
			}
		}
	}

	/**
	 * Builds array of references connected to $referenceableElement indexed by className so we can feed them to specific form fields
	 * @todo: for now this holds specific entities because ReferenceableEntitiesToIdsTransformer is wrong!!!
	 *
	 * @param ReferenceableElement $referenceableElement
	 * @return array
	 */
	protected function getReferencesDataArray(ReferenceableElement $referenceableElement) {
		$answer = [];
		$references = $referenceableElement->getReferences();
		foreach($references as $reference) {
			$baseEntity = $reference->getBaseEntity();
			$baseEntityClass = $this->referenceManager->getRealClassName($baseEntity);
			$answer[$baseEntityClass][] = $baseEntity;
		}
		return $answer;
	}
}