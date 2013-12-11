<?php
/**
* @copyright 2013 Instaclick Inc.
*/

namespace IC\Bundle\Base\SecurityBundle\Tests\Routing;

use IC\Bundle\Base\TestBundle\Test\ContainerAwareTestCase;
use IC\Bundle\Base\SecurityBundle\Routing\Router;

/**
* Router test
*
* @group ICBaseSecurityBundle
* @group Service
* @group Unit
*
* @author David Maignan <davidm@nationalfibre.net>
*/
class RouterTest extends ContainerAwareTestCase
{
    /**
     * @var \IC\Bundle\Base\SecurityBundle\Routing\Router
     */
    private $router;

    /**
     * @{@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->router = new Router($this->container, 'resource to load');
    }

    /**
     * Test valid host
     *
     * @param string $host
     * @param int    $expected
     *
     * @dataProvider dataProviderForValidHost
     */
    public function testIsValidHost($host, $expected)
    {
        $result = $this->router->isValidHost($host, array('adultlink', 'adultlink.dev', 'localhost:8080'));

        $this->assertEquals($result, $expected);
    }

    /**
     * Data provider for valid host
     *
     * @return array
     */
    public function dataProviderForValidHost()
    {
        return array(
            array('adultlink', 1),
            array('adultlink.dev', 1),
            array('localhost:8080', 1),
            array('localhost:443', 0),
            array('adultlink{proxy}', 0),
            array('adultlink\foo', 0),
            array('proxy.adultlink', 1),
            array('google', 0),
        );
    }
}
