<?php
/**
 * @copyright 2013 Instaclick Inc.
 */

namespace IC\Bundle\Base\SecurityBundle\Tests\Acl\Permission;

use IC\Bundle\Base\SecurityBundle\Acl\Permission\PermissionMap;
use IC\Bundle\Base\TestBundle\Test\TestCase;

/**
 * PermissionMapTest
 *
 * @group ICBaseSecurityBundle
 * @group Unit
 *
 * @author Danilo Cabello <daniloc@nationalfibre.net>
 */
class PermissionMapTest extends TestCase
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->permissionMap = new PermissionMap();
    }

    /**
     * Test getMasks.
     *
     * @param string $permission
     *
     * @dataProvider provideDataForPermissionMap
     */
    public function testGetMasks($permission)
    {
        $this->assertNotNull($this->permissionMap->getMasks($permission, null));
    }

    /**
     * Test contains.
     *
     * @param string $permission
     *
     * @dataProvider provideDataForPermissionMap
     */
    public function testContains($permission)
    {
        $this->assertTrue($this->permissionMap->contains($permission));
    }

    /**
     * Provide data for getMasks and contains.
     *
     * @return array
     */
    public function provideDataForPermissionMap()
    {
        return array(
            array('EXECUTE'),
            array('CONSUME'),
            array('CREATE'),
        );
    }
}
