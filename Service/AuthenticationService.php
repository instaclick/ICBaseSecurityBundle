<?php
/**
 * @copyright 2012 Instaclick Inc.
 */

namespace IC\Bundle\Base\SecurityBundle\Service;

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Authentication Service
 *
 * Authenticate a user.
 *
 * @author Juti Noppornpitak <jutin@nationalfibre.net>
 * @author Anthon Pang <anthonp@nationalfibre.net>
 * @author Yuan Xie <shayx@nationalfibre.net>
 * @author Oleksii Strutsynskyi <oleksiis@nationalfibre.net>
 */
class AuthenticationService
{
    /**
     * @var \Symfony\Component\Security\Core\SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var \Doctrine\Common\Persistence\ManagerRegistry
     */
    protected $managerRegistry;

    /**
     * Define the Security Context
     *
     * @param \Symfony\Component\Security\Core\SecurityContextInterface $securityContext
     */
    public function setSecurityContext(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * Define the Session
     *
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     */
    public function setSession(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Define the Doctrine Manager Registry.
     *
     * @param \Doctrine\Common\Persistence\ManagerRegistry $managerRegistry
     */
    public function setManagerRegistry(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * Define the Event Dispatcher
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Check if the current session is authenticated
     *
     * @return boolean
     */
    public function isAuthenticated()
    {
        try {
            return $this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')
                || $this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED');
        } catch (AuthenticationCredentialsNotFoundException $e) {
        }

        return false;
    }

    /**
     * Retrieve the authenticated user.
     *
     * @return null|\Symfony\Component\Security\Core\User\UserInterface
     */
    public function getAuthenticated()
    {
        $token = $this->securityContext->getToken();

        if ( ! $token) {
            return null;
        }

        $user = $token->getUser();

        if ( ! is_object($user)) {
            return null;
        }

        // We need to make Doctrine aware of this unserialized user entity.
        // $entityManager = $this->managerRegistry->getManagerForClass(get_class($user));
        // if ( ! $entityManager->contains($user)) {
        //     $entityManager->merge($user);
        // }

        return $user;
    }

    /**
     * Authenticate the given credential
     *
     * @param \Symfony\Component\Security\Core\User\UserInterface $credential
     * @param string                                              $firewallContext
     */
    public function authenticate(UserInterface $credential, $firewallContext)
    {
        $token      = new UsernamePasswordToken($credential, null, $firewallContext, $credential->getRoles());
        $sessionKey = '_security_' . $firewallContext;

        $this->securityContext->setToken($token);
        $this->dispatchInteractiveLoginEvent($token);

        // Note: this tiny hacking code is based on \Symfony\Component\Security\Http\Firewall::onKernelResponse.
        $this->session->set($sessionKey, serialize($this->securityContext->getToken()));
    }

    /**
     * Dispatch an interactive login event
     *
     * @param \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken $token
     */
    protected function dispatchInteractiveLoginEvent(UsernamePasswordToken $token)
    {
        $request    = new Request();
        $loginEvent = new InteractiveLoginEvent($request, $token);

        $this->eventDispatcher->dispatch(SecurityEvents::INTERACTIVE_LOGIN, $loginEvent);
    }
}
