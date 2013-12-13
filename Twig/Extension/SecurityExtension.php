<?php
/**
 * @copyright 2013 Instaclick Inc.
 */

namespace IC\Bundle\Base\SecurityBundle\Twig\Extension;

use JMS\SecurityExtraBundle\Security\Authorization\Expression\Expression;
use IC\Bundle\Base\SecurityBundle\Service\AuthorizationServiceInterface;

/**
 * SecurityExtension exposes security context features.
 *
 * @author Danilo Cabello <daniloc@nationalfibre.net>
 * @author Anthon Pang <anthonp@nationalfibre.net>
 */
class SecurityExtension extends \Twig_Extension
{
    /**
     * @var IC\Bundle\Base\SecurityBundle\Service\AuthorizationServiceInterface
     */
    private $authorizationService;

    /**
     * Define Authorization Service.
     *
     * @param IC\Bundle\Base\SecurityBundle\Service\AuthorizationServiceInterface $authorizationService
     */
    public function setAuthorizationService(AuthorizationServiceInterface $authorizationService)
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
        if ( ! $authorization) {
            return true;
        }

        $expression = array(new Expression($authorization));

        return $this->authorizationService->isGranted($expression);
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
