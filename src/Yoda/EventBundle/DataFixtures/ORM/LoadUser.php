<?php
/**
 * Created by PhpStorm.
 * User: rob
 * Date: 11/02/16
 * Time: 4:36 PM
 */

namespace Yoda\EventBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints\DateTime;
use Yoda\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUser implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $event1 = new User();
        $event1->setUsername('rob');
        $event1->setPassword($this->encodePassword($event1,'ed209'));
        $event1->setEmail('robcampbell73@gmail.com');
        $manager->persist($event1);

        $event2 = new User();
        $event2->setUsername('cambridge');
        $event2->setPassword($this->encodePassword($event2,'rod'));
        $event2->setRoles(array('ROLE_ADMIN'));
        $event2->setEmail('rodcambridge@hotmail.com');
        $manager->persist($event2);
        $manager->flush();
    }

    private function encodePassword(User $user, $plainPassword)
    {
        $encoder = $this->container->get('security.encoder_factory')
            ->getEncoder($user);
        return $encoder->encodePassword($plainPassword, $user->getSalt());
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 10;
    }
}
