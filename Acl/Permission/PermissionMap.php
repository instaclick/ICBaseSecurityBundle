<?php
/**
 * @copyright 2013 Instaclick Inc.
 */
namespace IC\Bundle\Base\SecurityBundle\Acl\Permission;

use Symfony\Component\Security\Acl\Permission\BasicPermissionMap;

/**
 * Map roles and entities with the associated permissions.
 *
 * @author John Cartwright <johnc@nationalfibre.net>
 * @author Danilo Cabello <daniloc@nationalfibre.net>
 */
class PermissionMap extends BasicPermissionMap
{
    const PERMISSION_EXECUTE = 'EXECUTE';
    const PERMISSION_CONSUME = 'CONSUME';

    /**
     * @var array
     */
    private $map = array(
        self::PERMISSION_EXECUTE => array(
            MaskBuilder::MASK_EXECUTE
        ),
        self::PERMISSION_CONSUME => array(
            MaskBuilder::MASK_CONSUME
        )
    );

    /**
     * {@inheritdoc}
     */
    public function getMasks($permission, $object)
    {
        if ( ! isset($this->map[$permission])) {
            return parent::getMasks($permission, $object);
        }

        return $this->map[$permission];
    }

    /**
     * {@inheritdoc}
     */
    public function contains($permission)
    {
        return isset($this->map[$permission]) || parent::contains($permission);
    }
}
