<?php
/**
 * @copyright 2013 Instaclick Inc.
 */
namespace IC\Bundle\Base\SecurityBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * Base class for ACLs permission data fixtures.
 *
 * @author John Cartwright <johnc@nationalfibre.net>
 * @author Danilo Cabello <daniloc@natilnalfibre.net>
 */
abstract class AbstractAclPermissionDataFixture extends AbstractAclDataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->getPermissionList() as $resourceName => $roleList) {
            $this->loadRoleList($resourceName, 'CONSUME', $roleList);
        }
    }
}
