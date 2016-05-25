<?php

namespace BackendBundle\Services;

use BackendBundle\Entity as Entity;
use Doctrine\ORM\EntityManager;
use Util\Util;

/*
 * TimeTracker
 * Esta clase implementa metodos necesarios para el modulo de TimeTracking
 */

class TimeTracker {

    const DEFAULT_DAYS_TO_SEARCH = 8;

    private $em;
    private $tokenStorage;

    /**
     * Constructor del servicio
     * @author Cesar Giraldo <cesargiraldo1108@gmail.com> 28/04/2016
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager, $tokenStorage) {
        $this->em = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Permite obtener el numero de segundos que hay entre dos fechas
     * @param \DateTime $startDate fecha inicial
     * @param \DateTime $endDate fecha final
     * @return integer numero total de segundos
     */
    public function getSecondsBetweenDates($startDate, $endDate) {
        $timeFirst = strtotime($startDate->format('Y-m-d H:i:s'));
        $timeSecond = strtotime($endDate->format('Y-m-d H:i:s'));
        $differenceInSeconds = $timeSecond - $timeFirst;
        return $differenceInSeconds;
    }

    /**
     * Permite obtener en lenguaje natural el tiempo que representa
     * una cantidad de segundos determinada
     * @author Cesar Giraldo <cesargiraldo1108@gmail.com> 27/04/2016
     * @param integer $secs canidad de segundos
     * @return string descripcion del tiempo transcurrido
     */
    public function getElapsedTime($secs) {
        $bit = array(
            'y' => $secs / 31556926 % 12,
            'w' => $secs / 604800 % 52,
            'd' => $secs / 86400 % 7,
            'h' => $secs / 3600 % 24,
            'm' => $secs / 60 % 60,
            's' => $secs % 60
        );

        foreach ($bit as $k => $v) {
            if ($v > 0) {
                $ret[] = $v . $k;
            }
        }

        return join(' ', $ret);
    }

    public function getWorkedTimePerDay($date, $userId, $natural = false) {

        $startDate = new \DateTime($date);
        $startDate->setTime(00, 00, 00);

        $endDate = new \DateTime($date);
        $endDate->setTime(23, 59, 59);

        $search = array('startDate' => $startDate, 'endDate' => $endDate);

        $workedTime = $this->em->getRepository('BackendBundle:TimeTracking')
                ->findWorkedTime($userId, $search);
        if ($natural) {
            return $this->getElapsedTime($workedTime);
        }
        return $workedTime;
    }

    
    /**
     * Permite verificar si el usuario logueado tiene un registro de tiempo
     * @author Cesar Giraldo <cesargiraldo1108@gmail.com> May 25 2016
     * @return \BackendBundle\Entity\TimeTracking
     */
    public function getActiveTimeTrack() {

        $user = $this->tokenStorage->getToken()->getUser();

        if ($user) {
            $searchActive = array(
                'user' => $user->getId(),
                'endTime' => null
            );
            $order = array('date' => 'DESC', 'startTime' => 'DESC');
            $timeTrack = $this->em->getRepository('BackendBundle:TimeTracking')->findOneBy($searchActive, $order);
            if (!$timeTrack instanceof Entity\TimeTracking) {
                $timeTrack = new Entity\TimeTracking();
                $timeTrack->setUser($user);
            } else {
                $workedTime = $this->getSecondsBetweenDates($timeTrack->getStartTime(), Util::getCurrentDate());
                $timeTrack->setWorkedTime($workedTime);
            }
            return $timeTrack;
        }
        return null;
    }

}
