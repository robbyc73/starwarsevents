<?php
/**
 * Created by PhpStorm.
 * User: rob
 * Date: 7/03/16
 * Time: 3:23 PM
 */

namespace Yoda\EventBundle\Event;
use Symfony\Component\EventDispatcher\Event;
use Yoda\EventBundle\Entity\Event;

class EventCreatedEvent extends Event{
 const NAME = 'event.created';

    protected $event;

    function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }


}