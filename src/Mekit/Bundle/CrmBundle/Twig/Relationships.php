<?php
namespace Mekit\Bundle\CrmBundle\Twig;

use Oro\Bundle\TranslationBundle\Translation\Translator;
use Symfony\Component\Routing\Router;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\Common\Inflector\Inflector;


class Relationships extends \Twig_Extension
{
	/** @var Router */
	protected $router;
	/** @var  Translator */
	protected $translator;

	/**
	 * @param Router     $router
	 * @param Translator $translator
	 */
	public function __construct(Router $router, Translator $translator) {
		$this->router = $router;
		$this->translator = $translator;
	}

	/**
	 * @return array
	 */
	public function getFunctions() {
		return array(
			new \Twig_SimpleFunction('mekit_related_datagrid_name', array(
				$this,
				'getRelatedDatagridName'
			))
		);
	}

	/**
	 * Returns the name of the datagrid which is displaying the related entities($name) for entity($entity): projects-related-tasks
	 * @param string $name
	 * @param object $entity
	 * @return string
	 */
	public function getRelatedDatagridName($name, $entity) {
		$refl = ClassUtils::newReflectionObject($entity);
		$className = strtolower(Inflector::pluralize($refl->getShortName()));
		$names = Inflector::pluralize($name);
		return $className . "-related-" . $names;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'mekit_crm_relationships';
	}
}