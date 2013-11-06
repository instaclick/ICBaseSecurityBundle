<?php
/**
 * @copyright 2013 Instaclick Inc.
 */

namespace IC\Bundle\Base\SecurityBundle\Tests\Resource;

use IC\Bundle\Base\SecurityBundle\Resource\Permission;
use IC\Bundle\Base\TestBundle\Test\TestCase;

/**
 * PermissionTest
 * 
 * @group ICBaseSecurityBundle
 * @group Unit
 * 
 * @author Danilo Cabello <daniloc@nationalfibre.net>
 */
class PermissionTest extends TestCase
{
    /**
     * Test Permission class.
     */
    public function testPermission()
    {
        $permission = new Permission('ic_base_security.service.view', 'EXECUTE');

        $this->assertEquals('ic_base_security.service.view', $permission->getResourceName());
        $this->assertEquals('EXECUTE', $permission->getMask());
        $this->assertInstanceOf('Symfony\Component\Security\Acl\Domain\ObjectIdentity', $permission->getObjectIdentity());
    }

    /**
     * Test Permission constructor defaults to CONSUME mask.
     */
    public function testPermissionDefaultToConsume()
    {
        $permission = new Permission('ic_base_security.service.view');

        $this->assertEquals('CONSUME', $permission->getMask());
    }
}
