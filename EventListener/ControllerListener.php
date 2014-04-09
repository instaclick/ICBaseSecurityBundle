<?php
/**
 * @copyright 2013 Instaclick Inc.
 */

namespace IC\Bundle\Base\SecurityBundle\EventListener;

use IC\Bundle\Base\SecurityBundle\Resource\SecuredResourceInterface;
use IC\Bundle\Base\SecurityBundle\Service\AuthorizationService;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Access Controller Listener
 *
 * @author John Cartwright <johnc@nationalfibre.net>
 * @author Anthon Pang <anthonp@nationalfibre.net>
 */
class ControllerListener
{
    /**
     * @var IC\Bundle\Base\SecurityBundle\Service\AuthorizationService
     */
    private $authorizationService;

    /**
     * @var Symfony\Component\Security\Core\SecurityContext
     */
    private $securityContext;

    /**
     * Define Authorization Service.
     *
     * @param \IC\Bundle\Base\SecurityBundle\Service\AuthorizationService $authorizationService
     */
    public function setAuthorizationService(AuthorizationService $authorizationService)
    {
        $this->authorizationService = $authorizationService;
    }

    /**
     * Define Security Context.
     *
     * @param \Symfony\Component\Security\Core\SecurityContext $securityContext
     */
    public function setSecurityContext(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * Validate the access to the kernel controller request.
     *
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        list($controller, $action) = $event->getController();

        $token = $this->securityContext->getToken();

        // Don't validate anything that is not configured behind a firewall
        if ( ! $token) {
            return;
        }

        if ( ! $controller instanceof SecuredResourceInterface) {
            return;
        }

        if ($token instanceof AnonymousToken) {
            throw new InsufficientAuthenticationException();
        }

        $permission = $controller->getPermission($action);

        if ( ! $this->authorizationService->isGranted($permission->getMask(), $permission->getResourceName())) {
            throw new InsufficientAuthenticationException();
        }
    }
}
