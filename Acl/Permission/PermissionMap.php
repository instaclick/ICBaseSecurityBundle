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
 * @author Anthon Pang <anthonp@nationalfibre.net>
 */
class PermissionMap extends BasicPermissionMap
{
    const PERMISSION_EXECUTE = 'EXECUTE';
    const PERMISSION_CONSUME = 'CONSUME';

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct();

        $this->map[self::PERMISSION_EXECUTE] = array(
            MaskBuilder::MASK_EXECUTE,
        );

        $this->map[self::PERMISSION_CONSUME] = array(
            MaskBuilder::MASK_CONSUME,
        );
    }
}
