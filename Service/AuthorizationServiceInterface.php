<?php
/**
 * @copyright 2013 Instaclick Inc.
 */

namespace IC\Bundle\Base\SecurityBundle\Service;

use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Authorization Service interface.
 *
 * @author Guilherme Blanco <gblanco@nationalfibre.net>
 * @author Kevin Thackorie <kevint@nationalfibre.net>
 */
interface AuthorizationServiceInterface
{
    /**
     * Define the Security Context
     *
     * @param \Symfony\Component\Security\Core\SecurityContextInterface $securityContext
     */
    public function setSecurityContext(SecurityContextInterface $securityContext);

    /**
     * Validate if authenticated token has a given permission.
     *
     * @param string $permission
     *
     * @throws \InvalidArgumentException
     *
     * @return boolean
     */
    public function hasPermission($permission);

    /**
     * Validate if authenticated token has a given object permission.
     *
     * @param string|array  $operation
     * @param string|object $domainObject
     *
     * @return boolean
     */
    public function isGranted($operation, $domainObject = null);
}
