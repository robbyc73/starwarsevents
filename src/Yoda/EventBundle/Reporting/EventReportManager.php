<?php
/**
 * Created by PhpStorm.
 * User: rob
 * Date: 26/02/16
 * Time: 8:50 AM
 */

namespace Yoda\EventBundle\Reporting;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Router;


class EventReportManager {

    private $em;
    private $router;

    public function __construct(EntityManager $em, Router $router)
    {
        $this->em = $em;
        $this->router = $router;
    }

    public function getRecentlyUpdated() {

        $events = $this->em->getRepository('EventBundle:Event')
            ->getRecentlyUpdatedEvents();

        $rows = array();
        foreach($events as $event) {
            $data = array(
                $event->getId(),
                $event->getName(),
                $event->getTime()->format('Y-m-d H:i:s'),
                $this->router->generate('event_show', array('slug' => $event->getSlug()), true)
            );

            $rows[] = implode(', ',$data);
        }
        $content = implode("\n", $rows);

        return $content;
    }
}