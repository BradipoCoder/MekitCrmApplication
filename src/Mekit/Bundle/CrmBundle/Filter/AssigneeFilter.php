<?php
namespace Mekit\Bundle\CrmBundle\Filter;

use Oro\Bundle\FilterBundle\Filter\ChoiceFilter;
use Oro\Bundle\FilterBundle\Filter\FilterUtility;
use Mekit\Bundle\CrmBundle\Form\Type\Filter\AssigneeFilterType;


class AssigneeFilter extends ChoiceFilter
{
	/**
	 * {@inheritDoc}
	 */
	public function init($name, array $params)
	{
		$params[FilterUtility::FRONTEND_TYPE_KEY] = 'choice';
		parent::init($name, $params);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getFormType()
	{
		return AssigneeFilterType::NAME;
	}

}