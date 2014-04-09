<?php
/**
 * @copyright 2012 Instaclick Inc.
 */

namespace IC\Bundle\Base\SecurityBundle\Tests\MockObject\Entity;

use IC\Bundle\Base\SecurityBundle\Entity\PasswordInterface;
use IC\Bundle\Base\SecurityBundle\Entity\Password;
use IC\Bundle\Base\ComponentBundle\Entity\Entity;

/**
 * Mock Credential Entity
 *
 * @author Oleksii Strutsynskyi <oleksiis@nationalfibre.net>
 */
class Credential extends Entity implements PasswordInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string Volatile field, used only during form, serialization and validation
     */
    protected $plainPassword;

    /**
     * @var string
     */
    protected $passwordHash;

    /**
     * @var string
     */
    protected $passwordSalt;

    /**
     * Get the id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     *
     * {@internal This is only required by the form generation, validation and serialization. }}
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * {@inheritdoc}
     *
     * {@internal This is only required by the form generation, validation and serialization. }}
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * Get the password.
     *
     * @return \IC\Bundle\Base\SecurityBundle\Entity\Password
     */
    public function getPasswordObject()
    {
        return new Password($this->passwordHash, $this->passwordSalt);
    }

    /**
     * Set the password.
     *
     * @param \IC\Bundle\Base\SecurityBundle\Entity\Password $password
     */
    public function setPasswordObject(Password $password)
    {
        $this->passwordHash = $password->getHash();
        $this->passwordSalt = $password->getSalt();
    }

    /**
     * Get the password.
     *
     * @return \IC\Bundle\Base\SecurityBundle\Entity\Password
     */
    public function getPassword()
    {
        return $this->passwordHash;
    }
}
