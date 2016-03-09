<?php

namespace Yoda\EventBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;
use Yoda\EventBundle\Controller\Controller;
use Yoda\EventBundle\Entity\Event;
use Yoda\EventBundle\Form\EventType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Doctrine\Common\Collections\Collection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Yoda\EventBundle\Event\EventCreatedEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;



/**
 * Event controller.
 *
 */
class EventController extends Controller
{

    /**
     * Lists all Event entities.
     * @Template()
     * @Route("/", name="event")
     */
    public function indexAction()
    {
     /*   $em = $this->getDoctrine()->getManager();

        $events = $em->getRepository('EventBundle:Event')->findAll();

        return array(
            'events' => $events,
        );*/
        return array();
    }

    public function _upcomingEventsAction($max = null)
    {
        $em = $this->getDoctrine()->getManager();

        $events = $em->getRepository('EventBundle:Event')
            ->getUpcomingEvents($max)
        ;

        return $this->render('EventBundle:Event:_upcomingEvents.html.twig', array(
            'events' => $events,
        ));
    }

    /**
     * Creates a new Event entity.
     *
     */
    public function newAction(Request $request)
    {

        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');

        $event = new Event();
        $form = $this->createForm('Yoda\EventBundle\Form\EventType', $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $event->setOwner($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            // create the EventCreatedEvent and dispatch it
            //$ecevent = new EventCreatedEvent($event);
            //$dispatcher->dispatch(EventCreatedEvent::NAME, $ecevent);

            return $this->redirectToRoute('event_show', array('slug' => $event->getSlug()));
        }

        return $this->render('event/new.html.twig', array(
            'event' => $event,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Event entity.
     *
     */
    public function showAction(Event $event)
    {
        $deleteForm = $this->createDeleteForm($event);

        return $this->render('event/show.html.twig', array(
            'event' => $event,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Event entity.
     *
     */
    public function editAction(Request $request, Event $event)
    {
        $this->enforceOwnerSecurity($event);
        $deleteForm = $this->createDeleteForm($event);
        $editForm = $this->createForm('Yoda\EventBundle\Form\EventType', $event);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('event_edit', array('slug' => $event->getSlug()));
        }

        return $this->render('event/edit.html.twig', array(
            'event' => $event,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Event entity.
     *
     */
    public function deleteAction(Request $request, Event $event)
    {
        $this->enforceOwnerSecurity($event);
        $form = $this->createDeleteForm($event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($event);
            $em->flush();
        }

        return $this->redirectToRoute('event_index');
    }

    /**
     * Creates a form to delete a Event entity.
     *
     * @param Event $event The Event entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Event $event)
    {
       // $this->enforceUserSecurity('ROLE_USER');
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('event_delete', array('slug' => $event->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


    public function attendAction($id,$format)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('EventBundle:Event')->find($id);

        if (!$event) {
            throw $this->createNotFoundException('No event found for id '.$id);
        }

        if (!$event->hasAttendee($this->getUser())) {
            $event->getAttendees()->add($this->getUser());
        }

        $em->persist($event);
        $em->flush();

        return $this->createAttendingResponse($event,$format);
    }

    public function unattendAction($id,$format)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('EventBundle:Event')->find($id);

        if (!$event) {
            throw $this->createNotFoundException('No event found for id '.$id);
        }

        if ($event->hasAttendee($this->getUser())) {
            $event->getAttendees()->removeElement($this->getUser());
        }

        $em->persist($event);
        $em->flush();

        return $this->createAttendingResponse($event,$format);

    }

    private function createAttendingResponse(Event $event,$format) {

        if ($format == 'json') {
            $data = array(
                'attending' => $event->hasAttendee($this->getUser())
            );
            //put collection into standard array
           /* if(!$event->getAttendees()->isEmpty()) {
                $attend = $event->getAttendees()->toArray();
                $attendees = array('attendees' => $attend);
                $data = array_merge($data,$attendees);
            }*/

            $response = new JsonResponse($data);

            return $response;
        }

            return $this->redirectToRoute('event_show', array('slug' => $event->getSlug()));

    }


}
