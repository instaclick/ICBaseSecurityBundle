<?php
/**
 * @copyright 2013 Instaclick Inc.
 */

namespace IC\Bundle\Base\SecurityBundle\Tests\DependencyInjection;

use IC\Bundle\Base\TestBundle\Test\DependencyInjection\ExtensionTestCase;

use IC\Bundle\Base\SecurityBundle\DependencyInjection\ICBaseSecurityExtension;

/**
 * Test for ICBaseSecurityExtension
 *
 * @group ICBaseSecurityBundle
 * @group Unit
 * @group DependencyInjection
 *
 * @author Yuan Xie <shayx@nationalfibre.net>
 */
class ICBaseSecurityExtensionTest extends ExtensionTestCase
{
    /**
     * Test configuration
     */
    public function testConfiguration()
    {
        $loader = new ICBaseSecurityExtension();

        $this->load($loader, array());
    }
}
