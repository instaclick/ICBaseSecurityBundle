<?php
/**
 * @copyright 2013 Instaclick Inc.
 */

namespace IC\Bundle\Base\SecurityBundle\Tests\Twig\Extension;

use IC\Bundle\Base\SecurityBundle\Twig\Extension\SecurityExtension;
use IC\Bundle\Base\TestBundle\Test\TestCase;
use Symfony\Component\ExpressionLanguage\Expression;

/**
 * SecurityExtensionTest
 *
 * @group ICBaseSecurityBundle
 * @group Twig
 * @group Unit
 *
 * @author Danilo Cabello <daniloc@nationalfibre.net>
 */
class SecurityExtensionTest extends TestCase
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->authorizationService = $this->createMock('IC\Bundle\Base\SecurityBundle\Service\AuthorizationService');

        $this->securityExtension = new SecurityExtension();
        $this->securityExtension->setAuthorizationService($this->authorizationService);
    }

    /**
     * Test hasPermission.
     *
     * @param boolean $expected
     * @param string  $permission
     * @param boolean $result
     *
     * @dataProvider provideDataForHasPermission
     */
    public function testHasPermission($expected, $permission, $result)
    {
        $this->authorizationService
             ->expects($this->once())
             ->method('hasPermission')
             ->with($this->equalTo($permission))
             ->will($this->returnValue($result));

        $this->assertEquals($expected, $this->securityExtension->hasPermission($permission));
    }

    /**
     * Provide data for hasPermission.
     *
     * @return array
     */
    public function provideDataForHasPermission()
    {
        return array(
            array(true,  'ic_base_security.service.view', true),
            array(false, 'ic_base_security.service.view', false),
        );
    }

    /**
     * Test is_authorized
     *
     * @param boolean $expected
     * @param string  $authorization
     * @param boolean $result
     *
     * @dataProvider provideDataForIsAuthorized
     */
    public function testIsAuthorized($expected, $authorization, $result)
    {
        $expression = array(new Expression($authorization));

        $this->authorizationService
             ->expects($this->once())
             ->method('isGranted')
             ->with($this->equalTo($expression))
             ->will($this->returnValue($result));

        $this->assertEquals($expected, $this->securityExtension->isAuthorized($authorization));
    }

    /**
     * Provide data for isAuthorized.
     *
     * @return array
     */
    public function provideDataForIsAuthorized()
    {
        return array(
            array(true,  'hasRole("ROLE_AFFILIATE_EXECUTIVE")', true),
            array(false, 'hasRole("ROLE_AFFILIATE_USER")', false),
        );
    }

    /**
     * Test is authorized results in granted for false expressions.
     *
     * @param mixed $authorization
     *
     * @dataProvider provideDataForIsAuthorizedResultsInGrantedForFalseExpressions
     */
    public function testIsAuthorizedResultsInGrantedForFalseExpressions($authorization)
    {
        $this->authorizationService
             ->expects($this->never())
             ->method('isGranted');

        $this->assertTrue($this->securityExtension->isAuthorized($authorization));
    }

    /**
     * Provide data for is authorized results in granted for false expressions.
     *
     * @return array
     */
    public function provideDataForIsAuthorizedResultsInGrantedForFalseExpressions()
    {
        return array(
            array(false),
            array(0),
            array("0"),
            array(null),
            array(array()),
            array(''),
        );
    }

    /**
     * Test getFunctions.
     */
    public function testGetFunctions()
    {
        $this->assertArrayHasKey('has_permission', $this->securityExtension->getFunctions());
        $this->assertArrayHasKey('is_authorized', $this->securityExtension->getFunctions());
    }

    /**
     * Test getName.
     */
    public function testGetName()
    {
        $this->assertEquals('ic_base_security.twig.extension.security', $this->securityExtension->getName());
    }
}
