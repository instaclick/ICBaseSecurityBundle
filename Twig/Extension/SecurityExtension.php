<?php
/**
 * @copyright 2013 Instaclick Inc.
 */

namespace IC\Bundle\Base\SecurityBundle\Twig\Extension;

use IC\Bundle\Base\SecurityBundle\Service\AuthorizationService;

/**
 * SecurityExtension exposes security context features.
 *
 * @author Danilo Cabello <daniloc@nationalfibre.net>
 * @author Anthon Pang <anthonp@nationalfibre.net>
 */
class SecurityExtension extends \Twig_Extension
{
    /**
     * @var IC\Bundle\Base\SecurityBundle\Service\AuthorizationService
     */
    private $authorizationService;

    /**
     * Define Authorization Service.
     *
     * @param IC\Bundle\Base\SecurityBundle\Service\AuthorizationService $authorizationService
     */
    public function setAuthorizationService(AuthorizationService $authorizationService)
    {
        $this->authorizationService = $authorizationService;
    }

    /**
     * Validate if authenticated token has a given permission.
     *
     * @param string $permission
     *
     * @return boolean
     */
    public function hasPermission($permission)
    {
        return $this->authorizationService->hasPermission($permission);
    }

    /**
     * Validate if authorized
     *
     * @param string $authorization
     *
     * @return boolean
     */
    public function isAuthorized($authorization)
    {
        return $this->authorizationService->isAuthorized($authorization);
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'has_permission' => new \Twig_Function_Method($this, 'hasPermission'),
            'is_authorized'  => new \Twig_Function_Method($this, 'isAuthorized'),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'ic_base_security.twig.extension.security';
    }
}
