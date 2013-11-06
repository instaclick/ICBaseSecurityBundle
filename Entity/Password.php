<?php
/**
 * @copyright 2012 Instaclick Inc.
 */

namespace IC\Bundle\Base\SecurityBundle\Entity;

/**
 * Entity of Password
 *
 * @author Juti Noppornpitak <jutin@nationalfibre.net>
 * @author Guilherme Blanco <gblanco@nationalfibre.net>
 * @author Oleksii Strutsynskyi <oleksiis@nationalfibre.net>
 */
class Password
{
    /**
     * @var string Password hash
     */
    protected $hash;

    /**
     * @var string Password salt
     */
    protected $salt;

    /**
     * Construct
     *
     * @param string $hash
     * @param string $salt
     */
    public function __construct($hash, $salt)
    {
        $this->hash = $hash;
        $this->salt = $salt;
    }

    /**
     * Get the password hash.
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Get the password salt.
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }
}
