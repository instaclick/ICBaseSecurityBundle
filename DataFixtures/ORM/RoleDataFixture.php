<?php
/**
 * @copyright 2013 Instaclick Inc.
 */
namespace IC\Bundle\Base\SecurityBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;

/**
 * Base class to load Role data fixtures
 *
 * @author Guilherme Blanco <gblanco@nationalfibre.net>
 */
abstract class RoleDataFixture extends AbstractFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $roleList = $this->getRoleList();

        foreach ($roleList as $referenceName => $roleName) {
            $this->referenceRepository->setReferenceIdentity($referenceName, new RoleSecurityIdentity($roleName));
        }
    }

    /**
     * Retrieve list of roles to be created.
     * Example:
     *
     * return new ArrayCollection(array(
     *     'role.foo' => 'ROLE_FOO'
     * ));
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    abstract protected function getRoleList();
}
