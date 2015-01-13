<?php
namespace Mekit\Bundle\RelationshipBundle\Twig;

use Oro\Bundle\EntityConfigBundle\Config\ConfigInterface;
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

	/**
	 * @param ReferenceManager $referenceManager
	 * @param WidgetExtension $widgetExtension
	 * @param Router $router
	 */
	public function __construct(ReferenceManager $referenceManager, WidgetExtension $widgetExtension, Router $router) {
		$this->referenceManager = $referenceManager;
		$this->widgetExtension = $widgetExtension;
		$this->router = $router;
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
			),
			new \Twig_SimpleFunction('mekit_render_relationship_items', array(
				$this,
				'renderRelationshipItems'),
				[
					'is_safe' => array('html'),
					'needs_environment' => true
				]
			)
		);
	}


	/**
	 * Render all relationships of a specific items of a specific ReferenceableElement
	 * @param Twig_Environment $environment,
	 * @param Array $options
	 * @return string
	 */
	public function renderAllRelationships(Twig_Environment $environment, $options) {
		if (!array_key_exists('referenceableElementId', $options) || empty($options["referenceableElementId"])) {
			throw new \InvalidArgumentException('Option referenceableElementId is required and cannot be empty!');
		}
		$options["widgetType"] = 'block';
		$options["url"] = $this->router->generate('mekit_relationship_widget_list', [
			"id" => $options["referenceableElementId"]
		]);
		return $this->widgetExtension->render($environment, $options);
	}

	/**
	 * Render items of a specific relationship
	 *
	 * @param Twig_Environment $environment,
	 * @param Array $options
	 * @return string
	 */
	public function renderRelationshipItems(Twig_Environment $environment, $options) {
		if (!array_key_exists('referenceableElementId', $options) || empty($options["referenceableElementId"])) {
			throw new \InvalidArgumentException('Option referenceableElementId is required and cannot be empty!');
		}
		if (!array_key_exists('referenceableEntityConfig', $options) || empty($options["referenceableEntityConfig"])) {
			throw new \InvalidArgumentException('Option referenceableEntityConfig is required and cannot be empty!');
		}
		/** @var ConfigInterface $referenceableEntityConfig */
		$referenceableEntityConfig = $options["referenceableEntityConfig"];

		$options["widgetType"] = 'block';
		$options["url"] = $this->router->generate('mekit_relationship_widget_related_items', [
			"id" => $options["referenceableElementId"],
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