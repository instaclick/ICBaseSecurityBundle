<?php
/**
 * @copyright 2012 Instaclick Inc.
 */

namespace IC\Bundle\Base\SecurityBundle\Routing;

use Symfony\Bundle\FrameworkBundle\Routing\Router as BaseRouter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

/**
 * When generating absolute URLs, we must validate the Host: header in the request
 * because this can be spoofed by a malicious user.
 *
 * @author Anthon Pang <anthonp@nationalfibre.net>
 */
class Router extends BaseRouter
{
    private $container;

    /**
     * {@inheritdoc}
     */
    public function __construct(ContainerInterface $container, $resource, array $options = array(), RequestContext $context = null)
    {
        $this->container = $container;

        parent::__construct($container, $resource, $options, $context);
    }

    /**
     * Validate "Host" (untrusted user input)
     *
     * @param string $host           Contents of Host: header from Request
     * @param array  $trustedDomains An array of trusted domains
     *
     * @return boolean True if valid; false otherwise
     */
    public function isValidHost($host, $trustedDomains)
    {
        // Only punctuation we allow is '[', ']', ':', '.' and '-'
        $hostLength = function_exists('mb_orig_strlen') ? mb_orig_strlen($host) : strlen($host);

        if (strcspn($host, '`~!@#$%^&*()_+={}\\|;"\'<>,?/ ') !== $hostLength) {
            return false;
        }

        $untrustedHost = function_exists('mb_strtolower') ? mb_strtolower($host) : strtolower($host);
        $domainRegex   = str_replace('.', '\.', '/(^|.)' . implode('|', $trustedDomains) . '(:[0-9]+)?$/');

        return preg_match($domainRegex, rtrim($untrustedHost, '.')) !== 0;
    }

    /**
     * {@inheritdoc}
     */
    public function generate($name, $parameters = array(), $absolute = false)
    {
        if ( ! $absolute) {
            return $this->getGenerator()->generate($name, $parameters, $absolute);
        }

        $validRoute = $this->container->get('kernel')->getEnvironment() === 'dev'
                   || $this->isValidHost($this->context->getHost(), $this->container->getParameter('trusted_domains'));

        if ($validRoute) {
            return $this->getGenerator()->generate($name, $parameters, $absolute);
        }

        throw new RouteNotFoundException(sprintf('The "%s" route requires a valid host.', $name));
    }
}
