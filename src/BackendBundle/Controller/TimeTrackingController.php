<?php

namespace BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BackendBundle\Entity as Entity;
use Util\Util;
use BackendBundle\Form\TimeTracking\TimeTrackType;

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

        //FIX_ME
        //buscamos si hay alhuna tarea activa para el usuario logueado
        $search['endTime'] = null;
        $timeTrack = $em->getRepository('BackendBundle:TimeTracking')->findOneBy($search, $order);
        if (!$timeTrack instanceof Entity\TimeTracking) {
            $timeTrack = new Entity\TimeTracking();
            $timeTrack->setUser($this->getUser());
        }
        $form = $this->createForm(TimeTrackType::class, $timeTrack);

        return $this->render('BackendBundle:TimeTracking:index.html.twig', array(
                    'time_track' => $timeTrack,
                    'time_tracking' => $timeTracking,
                    'form' => $form->createView(),
                    'menu' => self::MENU,
        ));
    }

    /**
     * Esta funcion permite obtener un listado de opciones con todos los items
     * activos de un proyecto.
     * @author Cesar Giraldo <cesargiraldo1108@gmail.com> 05/04/2016
     * @param Request $request
     * @return JsonResponse
     */
    public function getProjectItemsAction(Request $request) {

        $response = array('result' => '__OK__', 'msg' => '');
        $em = $this->getDoctrine()->getManager();

        $projectId = trim($request->request->get('projectId'));

        $project = $em->getRepository('BackendBundle:Project')->find($projectId);

        if (!$project || !$this->container->get('access_control')->isAllowedProject($projectId)) {
            $response['result'] = '__KO__';
            $response['msg'] = $this->get('translator')->trans('backend.item.not_found_message');
            return new JsonResponse($response);
        }

        try {

            $search = array('project' => $projectId,
                'status' => array(
                    Entity\Item::STATUS_NEW,
                    Entity\Item::STATUS_INVESTIGATING,
                    Entity\Item::STATUS_CONFIRMED,
                    Entity\Item::STATUS_BEING_WORKED_ON,
                    Entity\Item::STATUS_NEAR_COMPLETION,
                    Entity\Item::STATUS_TESTING,
            ));
            $order = array('consecutive' => 'DESC');

            $items = $em->getRepository('BackendBundle:Item')->findBy($search, $order);

            $html = $this->renderView('BackendBundle:TimeTracking:projectItems.html.twig', array(
                'items' => $items,
            ));

            $response['data']['html'] = $html;
        } catch (\Exception $ex) {
            $response['result'] = '__KO__';
            $response['msg'] = $this->get('translator')->trans('backend.global.unknown_error');
        }

        return new JsonResponse($response);
    }

    /**
     * Permite iniciar un contador de tiempo para una tarea determinada
     * @author Cesar Giraldo <cesargiraldo1108@gmail.com> 06/04/2016
     * @param Request $request
     * @return JsonResponse
     */
    public function startTimeAction(Request $request) {

        $response = array('result' => '__KO__', 'msg' => '');
        $em = $this->getDoctrine()->getManager();

        $timeTrack = new Entity\TimeTracking();
        $timeTrack->setUser($this->getUser());
        $form = $this->createForm(TimeTrackType::class, $timeTrack);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $parameters = $request->request->get('backendbundle_time_track_type');

            if (isset($parameters['taskId'])) {
                $item = $em->getRepository('BackendBundle:Item')->find($parameters['taskId']);

                if ($item instanceof Entity\Item &&
                        $this->container->get('access_control')->isAllowedProject($item->getProject()->getId())) {
                    $timeTrack->setItem($item);
                    $timeTrack->setProject($item->getProject());

                    $em->persist($timeTrack);
                    $em->flush();

                    $response['result'] = '__OK__';
                } else {
                    $response['msg'] = $this->get('translator')->trans('backend.item.not_found_message');
                }
            } else {
                $response['msg'] = $this->get('translator')->trans('backend.item.not_found_message');
            }
        } else {
            $response['msg'] = $this->get('translator')->trans('backend.global.unknown_error');
        }
        return new JsonResponse($response);
    }

}
