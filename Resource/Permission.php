<?php
/**
 * @copyright 2013 Instaclick Inc.
 */

namespace IC\Bundle\Base\SecurityBundle\Resource;

use Symfony\Component\Security\Acl\Domain\ObjectIdentity;

/**
 * Permission Resource.
 *
 * @author Guilherme Blanco <gblanco@nationalfibre.net>
 * @author John Cartwright <johnc@nationalfibre.net>
 */
class Permission
{
    /**
     * @var string
     */
    private $mask;

    /**
     * @var \Symfony\Component\Security\Acl\Domain\ObjectIdentity
     */
    private $objectIdentity;

    /**
     * Constructor.
     *
     * @param string $resourceName
     * @param string $mask
     */
    public function __construct($resourceName, $mask = 'CONSUME')
    {
        $this->objectIdentity = new ObjectIdentity('class', $resourceName);
        $this->mask           = $mask;
    }

    /**
     * Retrieve the resource name.
     *
     * @return string
     */
    public function getResourceName()
    {
        return $this->objectIdentity->getType();
    }

    /**
     * Retrieve the mask.
     *
     * @return string
     */
    public function getMask()
    {
        return $this->mask;
    }

    /**
     * Retrieve the object identity.
     *
     * @return \Symfony\Component\Security\Acl\Domain\ObjectIdentity
     */
    public function getObjectIdentity()
    {
        return $this->objectIdentity;
    }
}
