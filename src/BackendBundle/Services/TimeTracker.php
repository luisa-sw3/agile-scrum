<?php

namespace BackendBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use BackendBundle\Entity as Entity;

/*
 * TimeTracker
 * Esta clase implementa metodos necesarios para el modulo de TimeTracking
 */
class TimeTracker {

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
    
}
