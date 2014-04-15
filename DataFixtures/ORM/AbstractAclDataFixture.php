<?php
/**
 * @copyright 2013 Instaclick Inc.
 */
namespace IC\Bundle\Base\SecurityBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Exception\AclNotFoundException;
use IC\Bundle\Base\SecurityBundle\Acl\Permission\MaskBuilder;

/**
 * Base class for ACLs data fixtures
 *
 * @author John Cartwright <johnc@nationalfibre.net>
 * @author Danilo Cabello <daniloc@natilnalfibre.net>
 */
abstract class AbstractAclDataFixture extends AbstractFixture implements ContainerAwareInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load the role list.
     *
     * @param string $resourceName
     * @param string $permissionName
     * @param array  $roleList
     */
    protected function loadRoleList($resourceName, $permissionName, $roleList)
    {
        foreach ($roleList as $roleName) {
            $this->grantPermission($resourceName, $permissionName, $this->getReference($roleName));
        }
    }

    /**
     * Grant permission for a given resource and role.
     *
     * @param string                                              $resourceName
     * @param string                                              $permissionName
     * @param \Symfony\Component\Security\Core\Role\RoleInterface $role
     */
    private function grantPermission($resourceName, $permissionName, RoleInterface $role)
    {
        $aclProvider    = $this->container->get('security.acl.provider');
        $objectIdentity = new ObjectIdentity('class', $resourceName);

        try {
            $acl = $aclProvider->findAcl($objectIdentity);
        } catch (AclNotFoundException $exception) {
            $acl = $aclProvider->createAcl($objectIdentity);
        }

        $acl->insertClassAce(new RoleSecurityIdentity($role), $this->createMask($permissionName));

        $aclProvider->updateAcl($acl);
    }

    /**
     * Create mask from array of permitted operations.
     *
     * @param string $permission
     *
     * @return integer
     */
    private function createMask($permission)
    {
        $aclMaskBuilder   = new MaskBuilder();
        $aclPermissionMap = $this->container->get('security.acl.permission.map');

        foreach ($aclPermissionMap->getMasks($permission, null) as $permission) {
            $aclMaskBuilder->add($permission);
        }

        return $aclMaskBuilder->get();
    }

    /**
     * Retrieve the permission list.
     *
     * @return array
     */
    abstract public function getPermissionList();
}
