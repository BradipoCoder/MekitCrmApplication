<?php
namespace Mekit\Bundle\ListBundle\ImportExport\TemplateFixture;

use Oro\Bundle\ImportExportBundle\TemplateFixture\AbstractTemplateRepository;
use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateFixtureInterface;
use Mekit\Bundle\ListBundle\Entity\ListItem;

class ListItemFixture extends AbstractTemplateRepository implements TemplateFixtureInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function getEntityClass()
	{
		return 'Mekit\Bundle\ListBundle\Entity\ListItem';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getData()
	{
		return $this->getEntityData('ITEM');
	}

	/**
	 * {@inheritdoc}
	 */
	protected function createEntity($key)
	{
		return new ListItem();
	}

	/**
	 * @param string  $key
	 * @param ListItem $entity
	 */
	public function fillEntityData($key, $entity)
	{
		switch ($key) {
			case 'ITEM':
				$entity
					->setId(1)
					->setName("");
				return;
		}

		parent::fillEntityData($key, $entity);
	}
}