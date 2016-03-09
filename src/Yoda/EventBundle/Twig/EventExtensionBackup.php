<?php
/**
 * Created by PhpStorm.
 * User: rob
 * Date: 26/02/16
 * Time: 1:38 PM
 */

namespace Yoda\EventBundle\Twig;
use Symfony\Bundle\TwigBundle\DependencyInjection\TwigExtension;
use Twig_ExtensionInterface;
use Yoda\EventBundle\Util\DateUtil;

class EventExtension extends \TwigExtension {

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'event';
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('ago', array($this, 'calculateAgo')),
        );
    }

    public function calculateAgo(\DateTime $dt)
    {
        return DateUtil::ago($dt);
    }




}