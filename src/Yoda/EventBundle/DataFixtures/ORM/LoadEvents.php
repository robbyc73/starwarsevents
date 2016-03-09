<?php
// src/EventBundle/DataFixtures/ORM/LoadUserData.php
namespace Yoda\EventBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints\DateTime;
use Yoda\EventBundle\Entity\Event;

class LoadEvents implements FixtureInterface, OrderedFixtureInterface
{
public function load(ObjectManager $manager)
{
    $rob = $manager->getRepository('UserBundle:User')
        ->findOneByUsernameOremail('rob');

$event1  = new Event();
    $event1->setName('Emperor coming out party');
    $event1->setLocation('Endor');
    $event1->setTime(new \DateTime('tomorrow noon'));
    $event1->setDetails('Surprise!');
    $manager->persist($event1);
    $event2  = new Event();
    $event2->setName('Mum birthday');
    $event2->setLocation('Perth');
    $event2->setTime(new \DateTime('Thursday Noon'));
    $event2->setDetails('Surprise!');
$manager->persist($event2);

    $event1->setOwner($rob);
    $event2->setOwner($rob);

$manager->flush();
}

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
       return 20;
    }
}
