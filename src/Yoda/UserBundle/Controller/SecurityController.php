<?php
/**
 * Created by PhpStorm.
 * User: rob
 * Date: 11/02/16
 * Time: 10:46 AM
 */

namespace Yoda\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Yoda\EventBundle\Controller\Controller;



class SecurityController extends Controller
{

    /**
     * @Route("/login", name="login_form")
     * @Template
     */
    public function loginAction(){

        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

       /* return $this->render(
            'security/login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );*/
        return array(
            // last username entered by the user
            'last_username' => $lastUsername,
            'error'         => $error,
        );
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function logoutCheckAction()
    {
        //nothing goes in here
        // never execd

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
      //nothing goes in here
        // never execd

    }
}