<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {

    const MENU = 'menu_home';
    
    /**
     * Permite desplegar la home page del backend
     * @author Cesar Giraldo <cesargiraldo1108@gmail.com> 12/12/2015
     * @return type
     */
    public function indexAction() {

        return $this->render('BackendBundle:Default:index.html.twig', array(
            'menu' => self::MENU
        ));
    }

    /**
     * Esta funcion permite redireccionar a la aplicacion cuando no introducen 
     * la variable _locale en la url para identificar el idioma
     * @param Request $request
     * @return type
     */
    public function redirectAction(Request $request) {

        $locale = $this->container->getParameter('locale');
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER_ACTIVE')) {
            $url = $this->generateUrl('backend_homepage', array('_locale' => $locale));
        } else {
            $url = $this->generateUrl('backend_login', array('_locale' => $locale));
        }

        return $this->redirect($url);
    }

    public function dashboardAction() {
        return $this->render('BackendBundle:DesignExample:dashboard.html.twig');
    }

    public function chartsAction() {
        return $this->render('BackendBundle:DesignExample:charts.html.twig');
    }

    public function tablesAction() {
        return $this->render('BackendBundle:DesignExample:tables.html.twig');
    }

    public function formsAction() {
        return $this->render('BackendBundle:DesignExample:forms.html.twig');
    }

    public function bootstrapElementsAction() {
        return $this->render('BackendBundle:DesignExample:bootstrapElements.html.twig');
    }

    public function bootstrapGridAction() {
        return $this->render('BackendBundle:DesignExample:bootstrapGrid.html.twig');
    }

    public function blankPageAction() {
        return $this->render('BackendBundle:DesignExample:blankPage.html.twig');
    }

}
