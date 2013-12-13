<?php
/**
 * @copyright 2013 Instaclick Inc.
 */
namespace IC\Bundle\Base\SecurityBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Exception\AclNotFoundException;

/**
 * Base class to load permission data fixtures
 *
 * @author Guilherme Blanco <gblanco@nationalfibre.net>
 */
abstract class PermissionDataFixture extends AbstractFixture implements ContainerAwareInterface
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
        $aclProvider    = $this->container->get('security.acl.provider');
        $permissionList = $this->getPermissionList();

        foreach ($permissionList as $referenceName => $permissionData) {
            $permissionData = $this->normalizePermissionData($permissionData);
            $objectIdentity = new ObjectIdentity($permissionData['scope'], $permissionData['name']);

            try {
                $aclProvider->findAcl($objectIdentity);
            } catch (AclNotFoundException $exception) {
                $aclProvider->createAcl($objectIdentity);
            }

            $this->setReferenceIdentity($referenceName, $objectIdentity);
        }
    }

    /**
     * Normalize permission data.
     *
     * @param array|string $permissionData
     *
     * @return array
     */
    private function normalizePermissionData($permissionData)
    {
        if (is_array($permissionData)) {
            return $permissionData;
        }

        return array('name' => $permissionData, 'scope' => 'class');
    }

    /**
     * Retrieve list of permissions to be created.
     * Example:
     *
     * return new ArrayCollection(array(
     *     'permission.bar' => array(
     *         'name'  => 'bar',
     *         'scope' => 'class',
     *     ),
     *     // Using simplified behavior
     *     'permission.woo' => 'woo', // Infers scope = 'class'
     * ));
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    abstract protected function getPermissionList();
}
