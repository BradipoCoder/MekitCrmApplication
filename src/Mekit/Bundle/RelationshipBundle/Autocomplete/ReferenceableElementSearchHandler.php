<?php

namespace Mekit\Bundle\RelationshipBundle\Autocomplete;

use Oro\Bundle\FormBundle\Autocomplete\SearchHandler;

class ReferenceableElementSearchHandler extends SearchHandler
{

    /**
     * @param string $entityName
     * @param array $properties
     */
    public function __construct($entityName, array $properties)
    {
        parent::__construct($entityName, $properties);
    }

    /**
     * {@inheritdoc}
     */
    public function convertItem($user)
    {
        $result = parent::convertItem($user);
//        $result['avatar'] = null;
//
//        $avatar = $this->getPropertyValue('avatar', $user);
//        if ($avatar) {
//            $result['avatar'] = $this->attachmentManager->getFilteredImageUrl(
//                $avatar,
//                self::IMAGINE_AVATAR_FILTER
//            );
//        }

        return $result;
    }
}
