<?php
/**
 * @copyright 2013 Instaclick Inc.
 */
namespace IC\Bundle\Base\SecurityBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use IC\Bundle\Base\SecurityBundle\Acl\Permission\MaskBuilder;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class to load RolePermission data fixtures
 *
 * @author Guilherme Blanco <gblanco@nationalfibre.net>
 */
abstract class RolePermissionDataFixture extends AbstractFixture implements ContainerAwareInterface, DependentFixtureInterface
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
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $aclProvider        = $this->container->get('security.acl.provider');
        $rolePermissionList = $this->getRolePermissionList();


        foreach ($rolePermissionList as $rolePermissionData) {
            $rolePermissionData = $this->normalizeRolePermissionData($rolePermissionData);
            $objectIdentity     = $this->getReferenceIdentity($rolePermissionData['permission']);
            $securityIdentity   = $this->getReferenceIdentity($rolePermissionData['role']);
            $permissionMask     = $this->createPermissionMask($rolePermissionData['maskList']);
            $acl                = $aclProvider->findAcl($objectIdentity);

            $acl->insertClassAce($securityIdentity, $permissionMask);

            $aclProvider->updateAcl($acl);
        }
    }

    /**
     * Retrieve Reference Identity.
     *
     * @param string $name
     *
     * @return mixed
     */
    private function getReferenceIdentity($name)
    {
        $identityList = $this->referenceRepository->getIdentities();

        return $identityList[$name];
    }

    /**
     * Normalize Role-Permission data.
     *
     * @param array $rolePermissionData
     *
     * @return mixed
     */
    private function normalizeRolePermissionData($rolePermissionData)
    {
        if ( ! isset($rolePermissionData['maskList'])) {
            $rolePermissionData['maskList'] = array('owner');
        }

        return $rolePermissionData;
    }

    /**
     * Create permission mask.
     *
     * @param array $maskList
     *
     * @return integer
     */
    private function createPermissionMask($maskList)
    {
        $maskBuilder = new MaskBuilder();

        foreach ($maskList as $mask) {
            $maskBuilder->add($mask);
        }

        return $maskBuilder->get();
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getDependencies();

    /**
     * Retrieve list of roles to permissions to be created.
     * Example:
     *
     * return new ArrayCollection(array(
     *     'foo.bar' => array(
     *         'permission' => 'permission.bar',
     *         'role'       => 'role.foo',
     *         'maskList'   => array('owner'), // Inferred if not provided
     *     )
     * ))
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    abstract protected function getRolePermissionList();
}
