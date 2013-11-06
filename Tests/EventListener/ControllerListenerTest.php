<?php
/**
 * @copyright 2013 Instaclick Inc.
 */

namespace IC\Bundle\Base\SecurityBundle\Tests\EventListener;

use IC\Bundle\Base\SecurityBundle\EventListener\ControllerListener;
use IC\Bundle\Base\SecurityBundle\Resource\Permission;
use IC\Bundle\Base\TestBundle\Test\TestCase;

/**
 * ControllerListenerTest
 *
 * @group ICBaseSecurityBundle
 * @group EventListener
 * @group Symfony
 * @group Unit
 *
 * @author Danilo Cabello <daniloc@nationalfibre.net>
 */
class ControllerListenerTest extends TestCase
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->authorizationService = $this->createMock('IC\Bundle\Base\SecurityBundle\Service\AuthorizationService');
        $this->securityContext      = $this->createMock('Symfony\Component\Security\Core\SecurityContext');

        $this->controllerListener = new ControllerListener();
        $this->controllerListener->setAuthorizationService($this->authorizationService);
        $this->controllerListener->setSecurityContext($this->securityContext);
    }

    /**
     * Test when user has no token.
     */
    public function testNoToken()
    {
        $event = $this->createEvent('controller', 'action');

        $this->securityContext
             ->expects($this->once())
             ->method('getToken')
             ->will($this->returnValue(false));

        $this->controllerListener->onKernelController($event);
    }

    /**
     * Test controller that doesn't implement SecuredResourceInterface.
     */
    public function testNoSecuredResource()
    {
        $event = $this->createEvent(new \StdClass, 'action');

        $this->securityContext
             ->expects($this->once())
             ->method('getToken')
             ->will($this->returnValue(true));

        $this->controllerListener->onKernelController($event);
    }

    /**
     * Test secured resource with granted access.
     */
    public function testSecuredResourceGranted()
    {
        $controller = $this->createMock('IC\Bundle\Base\SecurityBundle\Resource\SecuredResourceInterface');
        $action     = 'action';
        $event      = $this->createEvent($controller, $action);
        $permission = new Permission('ic_base_security.service.view');

        $this->securityContext
             ->expects($this->once())
             ->method('getToken')
             ->will($this->returnValue(true));

        $controller
            ->expects($this->once())
            ->method('getPermission')
            ->with($action)
            ->will($this->returnValue($permission));

        $this->authorizationService
             ->expects($this->once())
             ->method('isGranted')
             ->with('CONSUME', 'ic_base_security.service.view')
             ->will($this->returnValue(true));

        $this->controllerListener->onKernelController($event);
    }

    /**
     * Test secured resource that deny access throws exception.
     *
     * @expectedException \Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException
     */
    public function testSecuredResourceDenied()
    {
        $controller = $this->createMock('IC\Bundle\Base\SecurityBundle\Resource\SecuredResourceInterface');
        $action     = 'action';
        $event      = $this->createEvent($controller, $action);
        $permission = new Permission('ic_base_security.service.view');

        $this->securityContext
             ->expects($this->once())
             ->method('getToken')
             ->will($this->returnValue(true));

        $controller
            ->expects($this->once())
            ->method('getPermission')
            ->with($action)
            ->will($this->returnValue($permission));

        $this->authorizationService
             ->expects($this->once())
             ->method('isGranted')
             ->with('CONSUME', 'ic_base_security.service.view')
             ->will($this->returnValue(false));

        $this->controllerListener->onKernelController($event);
    }

    /**
     * Create FilterControllerEvent
     *
     * @param object $controller
     * @param string $action
     *
     * @return Symfony\Component\HttpKernel\Event\FilterControllerEvent
     */
    private function createEvent($controller, $action)
    {
        $event = $this->createMock('Symfony\Component\HttpKernel\Event\FilterControllerEvent');

        $event->expects($this->once())
              ->method('getController')
              ->will($this->returnValue(array($controller, $action)));

        return $event;
    }
}
