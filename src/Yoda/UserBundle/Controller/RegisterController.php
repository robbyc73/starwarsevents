<?php
/**
 * Created by PhpStorm.
 * User: rob
 * Date: 18/02/16
 * Time: 3:53 PM
 */

namespace Yoda\UserBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Yoda\UserBundle\Entity\User;
use Yoda\UserBundle\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;
use Yoda\UserBundle\Form\RegisterType;
use Yoda\EventBundle\Controller\Controller;

class RegisterController extends Controller{

    /**
     * @Route("/register", name="user_register")
     * @Template()
     */
    public function registerAction(Request $request)
    {

        $data = array('username' => 'Your Username');
        $user = new User();



        $form = $this->createForm('Yoda\UserBundle\Form\RegisterType',$user,array());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();


            $user->setPassword($this->encodePassword($user, $user->getPlainPassword()));

            $em= $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $request->getSession()->getFlashbag()
                ->add('success', 'Welcome to the Death star! Have a magical day!');

            return $this->redirect($this->generateUrl('event_index'));

        }

        return array('form' => $form->createView());
    }

    private function encodePassword(User $user, $plainPassword)
    {
        $encoder = $this->container->get('security.encoder_factory')
            ->getEncoder($user);
        return $encoder->encodePassword($plainPassword, $user->getSalt());
    }

    private function authenticateUser(User $user)
    {
        $providerKey = 'secured_area';
        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());

        $this->getSecurityContext()->setToken($token);
    }
}