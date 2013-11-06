<?php
/**
 * @copyright 2012 Instaclick Inc.
 */

namespace IC\Bundle\Base\SecurityBundle\Entity;

use IC\Bundle\Base\SecurityBundle\Entity\Password;

/**
 * Password Interface
 *
 * @author Oleksii Strutsynskyi <oleksiis@nationalfibre.net>
 */
interface PasswordInterface
{
    /**
     * Get Plain Password
     *
     * @return string
     */
    public function getPlainPassword();

    /**
     * Set Plain password.
     *
     * @param string $password
     */
    public function setPlainPassword($password);

    /**
     * Get  Password
     *
     * @return \IC\Bundle\Base\SecurityBundle\Entity\Password
     */
    public function getPasswordObject();

    /**
     * Set Plain password.
     *
     * @param \IC\Bundle\Base\SecurityBundle\Entity\Password $password
     */
    public function setPasswordObject(Password $password);
}
