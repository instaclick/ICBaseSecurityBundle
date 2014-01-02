<?php
/**
 * @copyright 2013 Instaclick Inc.
 */

namespace IC\Bundle\Base\SecurityBundle\Service;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * AccessToken Service Interface
 *
 * @author Paul Munson <pmunson@nationalfibre.net>
 */
interface AccessTokenServiceInterface
{
    /**
     * Create an Access Token.
     *
     * @param \Symfony\Component\Security\Core\User\UserInterface $user          User
     * @param string                                              $scope         Scope
     * @param integer                                             $tokenLifetime Token lifetime
     *
     * @return string Access token string.
     */
    public function create(UserInterface $user, $scope, $tokenLifetime = null);

    /**
     * Verify an Access Token for the given scope.
     *
     * @param string $tokenString Token string
     * @param string $scope       Scope
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function validate($tokenString, $scope);

    /**
     * Delete an Access Token
     *
     * @param mixed $token Access Token
     */
    public function delete($token);
}
