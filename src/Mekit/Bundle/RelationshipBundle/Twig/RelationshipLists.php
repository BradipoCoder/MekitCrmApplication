<?php
namespace Mekit\Bundle\RelationshipBundle\Twig;

use Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement;
use Oro\Bundle\EntityConfigBundle\Config\ConfigInterface;
use Oro\Bundle\TranslationBundle\Translation\Translator;
use Symfony\Component\Routing\Router;
use Oro\Bundle\EntityConfigBundle\Provider\ConfigProvider;
use Oro\Bundle\UIBundle\Twig\WidgetExtension;
use Twig_Environment;
use Mekit\Bundle\RelationshipBundle\Entity\Manager\ReferenceManager;

class RelationshipLists extends \Twig_Extension {
	/** @var ReferenceManager */
	protected $referenceManager;

	/** @var WidgetExtension */
	protected $widgetExtension;

	/** @var Router */
	protected $router;

	/** @var  Translator */
	protected $translator;

	/**
	 * @param ReferenceManager $referenceManager
	 * @param WidgetExtension $widgetExtension
	 * @param Router $router
	 * @param Translator $translator
	 */
	public function __construct(ReferenceManager $referenceManager, WidgetExtension $widgetExtension, Router $router, Translator $translator) {
		$this->referenceManager = $referenceManager;
		$this->widgetExtension = $widgetExtension;
		$this->router = $router;
		$this->translator = $translator;
	}


	/**
	 * @return array
	 */
	public function getFunctions() {
		return array(
			new \Twig_SimpleFunction('mekit_render_all_relationships', array(
				$this,
				'renderAllRelationships'),
				[
					'is_safe' => array('html'),
					'needs_environment' => true
				]
			)
		);
	}

	/**
	 * Render all relationships
	 * @param Twig_Environment $environment,
	 * @param Array $options
	 * @return string
	 */
	public function renderAllRelationships(Twig_Environment $environment, $options) {
		if (!array_key_exists('referenceableElement', $options) || !$options["referenceableElement"] instanceof ReferenceableElement) {
			throw new \InvalidArgumentException('Option referenceableElement is required and must be an instance of Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement!');
		}
		/** @var ReferenceableElement $referenceableElement */
		$referenceableElement = $options["referenceableElement"];
		$className = get_class($referenceableElement->getBaseEntity());
		$classConfig = $this->referenceManager->getRelationshipConfiguration($className);
		if(!$classConfig || $classConfig->get("referenceable") !== true) {
			throw new \InvalidArgumentException('This is not a referenceable class('.$className.')!');
		}
		$referenceableEntityConfigs = $this->referenceManager->getReferenceableEntityConfigurations();
		$blocks = [];
		foreach($referenceableEntityConfigs as $referenceableEntityConfig) {
			$refClassName = $referenceableEntityConfig->getId()->getClassName();
			$blocks[] = [
				"title" => $this->translator->trans($referenceableEntityConfig->get("label")),
				"subblocks" => [
					[
						"data" => [
							$this->renderRelationshipItems($environment, [
								'referenceableElement' => $referenceableElement,
								'referenceableEntityConfig' => $referenceableEntityConfig
							])
						]
					]
				]
			];
		}
		return $blocks;
	}

	/**
	 * Render items of a specific relationship
	 *
	 * @param Twig_Environment $environment,
	 * @param Array $options
	 * @return string
	 */
	private function renderRelationshipItems(Twig_Environment $environment, $options) {
		if (!array_key_exists('referenceableElement', $options) || !$options["referenceableElement"] instanceof ReferenceableElement) {
			throw new \InvalidArgumentException('Option referenceableElement is required and must be an instance of Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement!');
		}
		if (!array_key_exists('referenceableEntityConfig', $options) || !$options["referenceableEntityConfig"] instanceof ConfigInterface) {
			throw new \InvalidArgumentException('Option referenceableEntityConfig is required and must be an instance of Oro\Bundle\EntityConfigBundle\Config\ConfigInterface!');
		}
		/** @var ReferenceableElement $referenceableElement */
		$referenceableElement = $options["referenceableElement"];
		/** @var ConfigInterface $referenceableEntityConfig */
		$referenceableEntityConfig = $options["referenceableEntityConfig"];
		$options["widgetType"] = 'block';
		$options["url"] = $this->router->generate('mekit_relationship_widget_related_items', [
			"id" => $referenceableElement->getId(),
			"type" => $referenceableEntityConfig->getId()->getClassName(),
		]);
		return $this->widgetExtension->render($environment, $options);
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'mekit_relationship_lists_ext';
	}
}