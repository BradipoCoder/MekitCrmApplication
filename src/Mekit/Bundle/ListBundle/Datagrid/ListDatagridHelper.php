<?php
namespace Mekit\Bundle\ListBundle\Datagrid;

use Doctrine\ORM\Query;
use Doctrine\ORM\EntityRepository;
use Mekit\Bundle\ListBundle\Entity\Repository\ListItemRepository;

class ListDatagridHelper {
	/**
	 * @var ListItemRepository
	 */
	private $listItemRepository;

	public function __construct(ListItemRepository $listItemRepository) {
		$this->listItemRepository = $listItemRepository;
	}

	/**
	 * @todo: works but very ugly! - we need custom type (extending entity type) where we can specify "group_name" explicitly
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	public function getListItemQueryBuilderForGroup() {
		$args = func_get_args();
		if(isset($args) && isset($args[2]) && isset($args[2]["attr"]) && isset($args[2]["attr"]["group_name"])) {
			return($this->listItemRepository->getListItemQueryBuilder($args[2]["attr"]["group_name"]));
		} else {
			throw new \LogicException("Missing 'group_name' parameter in passed arguments: " . json_encode($args));
		}
	}
}