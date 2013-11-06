<?php
/**
 * @copyright 2013 Instaclick Inc.
 */

namespace IC\Bundle\Base\SecurityBundle\Acl\Permission;

use Symfony\Component\Security\Acl\Permission\MaskBuilder as SymfonyMaskBuilder;

/**
 * Mask Builder
 *
 * @author John Cartwright <johnc@nationalfibre.net>
 * @author Danilo Cabello <daniloc@nationalfibre.net>
 */
class MaskBuilder extends SymfonyMaskBuilder
{
    const MASK_EXECUTE = 256;
    const MASK_CONSUME = 512;
}
