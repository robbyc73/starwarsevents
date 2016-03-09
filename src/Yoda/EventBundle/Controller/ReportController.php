<?php
/**
 * Created by PhpStorm.
 * User: rob
 * Date: 25/02/16
 * Time: 4:34 PM
 */

namespace Yoda\EventBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Yoda\EventBundle\Reporting\EventReportManager;

class ReportController extends Controller {

    /**
     * @Route("/events/report/recentlyUpdated.csv")
     */
    public function updatedEventsAction()
    {
        //echo $this->container->getParameter('host');
        //event report manager being a custom service
        $response = new Response($this->get('event_report_manager')->getRecentlyUpdated());

        $response->headers->set('Content-Type', 'text/csv');

        return $response;
    }
}