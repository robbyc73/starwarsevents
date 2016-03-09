<?php

namespace Yoda\TestServicesBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;



class DefaultController extends Controller
{
    public function indexAction()
    {
        $value = $this->get('testservice.firsttestservice')->returnSomeData();


        return $this->render('TestServicesBundle:Default:index.html.twig', array('value' => $value));
    }
}
