<?php
/**
 * @copyright 2013 Instaclick Inc.
 */
namespace IC\Bundle\Base\SecurityBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * Base class for ACLs resource data fixtures.
 *
 * @author John Cartwright <johnc@nationalfibre.net>
 * @author Danilo Cabello <daniloc@natilnalfibre.net>
 */
abstract class AbstractAclResourceDataFixture extends AbstractAclDataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->getPermissionList() as $resourceName => $permissionList) {
            $this->loadPermissionList($resourceName, $permissionList);
        }
    }

    /**
     * Load the permission list.
     *
     * @param string $resourceName
     * @param array  $permissionList
     */
    private function loadPermissionList($resourceName, $permissionList)
    {
        foreach ($permissionList as $permissionName => $roleList) {
            $this->loadRoleList($resourceName, $permissionName, $roleList);
        }
    }
}
