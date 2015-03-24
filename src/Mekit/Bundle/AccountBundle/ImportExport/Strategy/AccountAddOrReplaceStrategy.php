<?php

namespace Mekit\Bundle\AccountBundle\ImportExport\Strategy;

use Doctrine\Common\Util\ClassUtils;

use Oro\Bundle\ImportExportBundle\Strategy\Import\ConfigurableAddOrReplaceStrategy;
use Mekit\Bundle\AccountBundle\Entity\Account;

class AccountAddOrReplaceStrategy extends ConfigurableAddOrReplaceStrategy
{
    /**
     * {@inheritdoc}
     */
    protected function importExistingEntity(
        $entity,
        $existingEntity,
        $itemData = null,
        array $excludedFields = array()
    ) {
        parent::importExistingEntity($entity, $existingEntity, $itemData, $excludedFields);
    }
}
