<?php
/**
 * @copyright 2012 Instaclick Inc.
 */

namespace IC\Bundle\Base\SecurityBundle\Service;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Authentication Service Interface
 *
 * Interface to be implemented by authentication services
 *
 * @author Yuan Xie <shayx@nationalfibre.net>
 * @author Paul Munson <pmunson@nationalfibre.net>
 */
interface AuthenticationServiceInterface
{
    /**
     * Retrieve the authenticated user
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function getAuthenticated();

    /**
     * Authenticate the given user
     *
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return boolean
     */
    public function authenticate(UserInterface $user);

    /**
     * Check if the current session is authenticated
     *
     * @return boolean
     */
    public function isAuthenticated();
}
