<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;


umask(0000);

$loader = require_once __DIR__.'/../var/bootstrap.php.cache';

//Debug::enable();

require_once __DIR__.'/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$kernel->boot();

$container = $kernel->getContainer();
$container->enterScope('request');
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