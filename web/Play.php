<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;
use Symfony\Component\ClassLoader\ClassCollectionLoader;


umask(0000);

$loader = require __DIR__.'/../app/autoload.php';

//Debug::enable();

require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$kernel->boot();

$container = $kernel->getContainer();
//$container->enterScope('request');
$container->set('request', $request);

// all our setup is done!!!!!!

use Doctrine\ORM\EntityManager;

/** @var EntityManager $em */
$em = $container->get('doctrine')->getManager();

$rob = $em->getRepository('UserBundle:User')
    ->findOneByUsernameOrEmail('rob');

foreach($rob->getEvents() as $event)
{
    var_dump($event->getName());
}