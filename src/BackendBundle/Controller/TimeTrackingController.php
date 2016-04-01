<?php

namespace BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BackendBundle\Entity as Entity;
use Util\Util;

/**
 * TimeTracking controller.
 */
class TimeTrackingController extends Controller {

    const MENU = 'menu_time_tracking';

    /**
     * Permite listar los registros de tiempo trabajado de los ultimos dias
     * para el usuario logueado
     * @author Cesar Giraldo <cesargiraldo1108@gmail.com> 22/03/2016
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $search = array('user' => $this->getUser()->getId());
        $order = array('date' => 'DESC', 'startTime' => 'DESC');
        $timeTracking = $em->getRepository('BackendBundle:TimeTracking')->findBy($search, $order);

        return $this->render('BackendBundle:TimeTracking:index.html.twig', array(
                    'time_tracking' => $timeTracking,
                    'menu' => self::MENU
        ));
    }
}
