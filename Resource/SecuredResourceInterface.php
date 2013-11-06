<?php
/**
 * @copyright 2013 Instaclick Inc.
 */

namespace IC\Bundle\Base\SecurityBundle\Resource;

/**
 * Secured Resource Interface.
 *
 * @author Guilherme Blanco <gblanco@nationalfibre.net>
 * @author John Cartwright <johnc@nationalfibre.net>
 */
interface SecuredResourceInterface
{
    /**
     * Retrieve the Permission.
     *
     * @param string $action Controller action to be called
     *
     * @return \IC\Bundle\Base\SecurityBundle\Resource\Permission
     */
    public function getPermission($action = null);
}
