<?php
/**
 * @copyright 2012 Instaclick Inc.
 */

namespace IC\Bundle\Base\SecurityBundle\Service;

use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Authorization Service
 *
 * @author Anthon Pang <anthonp@nationalfibre.net>
 * @author Kinn Juliao <kinnj@nationalfibre.net>
 * @author John Cartwright <johnc@nationalfibre.net>
 * @author Danilo Cabello <daniloc@nationalfibre.net>
 */
class AuthorizationService implements AuthorizationServiceInterface
{
    /**
     * @var \Symfony\Component\Security\Core\SecurityContextInterface
     */
    private $securityContext;

    /**
     * Define the Security Context
     *
     * @param \Symfony\Component\Security\Core\SecurityContextInterface $securityContext
     */
    public function setSecurityContext(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * Validate if authenticated token has a given permission.
     *
     * @param string $permission
     *
     * @throws \InvalidDomainObjectException
     *
     * @return boolean
     */
    public function hasPermission($permission)
    {
        $objectIdentity = $this->createObjectIdentity($permission);

        if ( ! $this->securityContext->getToken()) {
            return false;
        }

        return $this->securityContext->isGranted('CONSUME', $objectIdentity);
    }

    /**
     * Validate if authenticated token has a given object permission.
     *
     * @param string|array  $operation
     * @param string|object $domainObject
     *
     * @return boolean
     */
    public function isGranted($operation, $domainObject = null)
    {
        $objectIdentity = $domainObject
            ? $this->createObjectIdentity($domainObject)
            : null;

        return $this->securityContext->isGranted($operation, $objectIdentity);
    }

    /**
     * Create the object identity.
     *
     * @param mixed $domainObject string|object
     *
     * @return \Symfony\Component\Security\Acl\Domain\ObjectIdentity
     */
    private function createObjectIdentity($domainObject)
    {
        if (is_string($domainObject)) {
            return new ObjectIdentity('class', $domainObject);
        }

        return ObjectIdentity::fromDomainObject($domainObject);
    }
}
