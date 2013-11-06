<?php
/**
 * @copyright 2012 Instaclick Inc.
 */

namespace IC\Bundle\Base\SecurityBundle\Service;

use JMS\SecurityExtraBundle\Security\Authorization\Expression\Expression;
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
class AuthorizationService
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
     * @throws \InvalidArgumentException
     *
     * @return boolean
     */
    public function hasPermission($permission)
    {
        if ( ! is_string($permission)) {
             throw new \InvalidArgumentException('Expected string as permission.');
        }

        return $this->isGranted('CONSUME', $permission);
    }

    /**
     * Validate if authenticated token has a given object permission.
     *
     * @param string $operation    string
     * @param mixed  $domainObject string|object
     *
     * @return boolean
     */
    public function isGranted($operation, $domainObject)
    {
        $objectIdentity = $this->createObjectIdentity($domainObject);

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

    /**
     * Is user authorized?
     *
     * @param mixed $authorization Authorization check using ROLE_ attribute or expression-based authorization language
     *
     * @return boolean
     *
     * @deprecated Use isGranted or hasPermission on this same class instead.
     */
    public function isAuthorized($authorization)
    {
        if ( ! $authorization) {
            return true;
        }

        $expression = array(new Expression($authorization));

        return $this->securityContext->isGranted($expression);
    }
}
