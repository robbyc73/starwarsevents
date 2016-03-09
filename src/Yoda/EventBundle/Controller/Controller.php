<?php
/**
 * Created by PhpStorm.
 * User: rob
 * Date: 22/02/16
 * Time: 10:56 AM
 */

namespace Yoda\EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Yoda\EventBundle\Entity\Event;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class Controller extends BaseController {

    /**
     * @return \Symfony\Component\Security\Core\SecurityContext
     */
    public function getSecurityContext() {
        return $this->get('security.authorization_checker');
    }

    public function enforceOwnerSecurity(Event $event)
    {
        $user = $this->getUser();
        if($user != $event->getOwner()) {
            throw new \Symfony\Component\Finder\Exception\AccessDeniedException('You don\'t own this!');
        }

    }

    public function enforceUserSecurity($role = 'ROLE_USER')
    {
        if(!$this->getSecurityContext()->isGranted($role)) {
            throw new \Symfony\Component\Finder\Exception\AccessDeniedException('Need '.$role);
        }

    }

}