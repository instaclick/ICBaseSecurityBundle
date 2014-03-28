<?php
/**
 * @copyright 2012 Instaclick Inc.
 */

namespace IC\Bundle\Base\SecurityBundle\Tests\Service;

use IC\Bundle\Base\SecurityBundle\Service\AuthorizationService;
use IC\Bundle\Base\TestBundle\Test\TestCase;
use JMS\SecurityExtraBundle\Security\Authorization\Expression\Expression;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Core\Util\ClassUtils;

/**
 * Authorization Service Test
 *
 * @group ICBaseSecurityBundle
 * @group Service
 * @group Unit
 *
 * @author Anthon Pang <anthonp@nationalfibre.net>
 * @author Kinn Juliao <kinnj@nationalfibre.net>
 * @author Danilo Cabello <daniloc@nationalfibre.net>
 */
class AuthorizationServiceTest extends TestCase
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->securityContext      = $this->createMock('Symfony\Component\Security\Core\SecurityContextInterface');
        $this->authorizationService = new AuthorizationService;

        $this->authorizationService->setSecurityContext($this->securityContext);
    }

    /**
     * Test hasPermission uses security context to check permission.
     *
     * @param boolean $expected
     * @param string  $permission
     * @param boolean $getToken
     * @param boolean $isGranted
     *
     * @dataProvider provideDataForHasPermission
     */
    public function testHasPermissionChecksForConsumePermission($expected, $permission, $getToken, $isGranted)
    {
        $this->securityContext
            ->expects($this->once())
            ->method('getToken')
            ->will($this->returnValue($getToken));

        $this->securityContext
             ->expects($this->any())
             ->method('isGranted')
             ->with($this->equalTo('CONSUME'), $this->equalTo(new ObjectIdentity('class', $permission)))
             ->will($this->returnValue($isGranted));

        $this->assertEquals($expected, $this->authorizationService->hasPermission($permission));
    }

    /**
     * Provide data for hasPermission
     *
     * @return array
     */
    public function provideDataForHasPermission()
    {
        return array(
            array(true,  'ic_base_security.service.view', true, true),
            array(false, 'ic_base_security.service.view', false, true),
            array(false, 'ic_base_security.service.view', true, false),
        );
    }

    /**
     * [testIsGranted description]
     *
     * @param boolean                                              $expected
     * @param string                                               $operation
     * @param string|object                                        $domainObject
     * @param Symfony\Component\Security\Acl\Domain\ObjectIdentity $objectIdentity
     * @param boolean                                              $result
     *
     * @dataProvider provideDataForIsGranted
     */
    public function testIsGranted($expected, $operation, $domainObject, $objectIdentity, $result)
    {
        $this->securityContext
             ->expects($this->once())
             ->method('isGranted')
             ->with($this->equalTo($operation), $this->equalTo($objectIdentity))
             ->will($this->returnValue($result));

        $this->assertEquals($expected, $this->authorizationService->isGranted($operation, $domainObject));
    }

    /**
     * Provide data for isGranted
     *
     * @return array
     */
    public function provideDataForIsGranted()
    {
        return array(
            array(true,  'CONSUME', 'ic_base_security.service.view', new ObjectIdentity('class', 'ic_base_security.service.view'), true),
            array(false, 'CONSUME', 'ic_base_security.service.view', new ObjectIdentity('class', 'ic_base_security.service.view'), false),
            array(false, 'CONSUME', $this->createDomainObject(),     new ObjectIdentity('getObjectIdentifier()', ClassUtils::getRealClass($this->createDomainObject())), false),
        );
    }

    /**
     * Create domain object that implements DomainObjectInterface.
     *
     * @return object PHPUnit Mock
     */
    public function createDomainObject()
    {
        $domainObject = $this->createMock('Symfony\Component\Security\Acl\Model\DomainObjectInterface');
        $domainObject
            ->expects($this->once())
            ->method('getObjectIdentifier')
            ->will($this->returnValue('getObjectIdentifier()'));

        return $domainObject;
    }

    /**
     * Provide data for isAuthorized.
     *
     * @return array
     */
    public function provideDataForIsAuthorized()
    {
        return array(
            array(true,  null, null, false),
            array(true,  false, null, false),
            array(true,  '', null, false),
            array(true,  'ROLE_ADMIN', array(new Expression('ROLE_ADMIN')), true),
            array(false, 'ROLE_ADMIN', array(new Expression('ROLE_ADMIN')), false),
            array(true,  'hasRole("ROLE_ADMIN")', array(new Expression('hasRole("ROLE_ADMIN")')), true),
            array(false, 'hasRole("ROLE_ADMIN")', array(new Expression('hasRole("ROLE_ADMIN")')), false),
        );
    }
}
