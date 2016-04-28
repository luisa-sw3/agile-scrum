<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TimeTrackingRepository extends EntityRepository {

    public function findUserTimeTracking($userId, $search = array()) {

        $em = $this->getEntityManager();

        $extraQuery = '';
        $parameters = array('userId' => $userId);
        if (isset($search['startDate'])) {
            $extraQuery .= ' AND time.date >= :startDate ';
            $parameters['startDate'] = $search['startDate'];
        }
        
        if (isset($search['endDate'])) {
            $extraQuery .= ' AND time.date <= :endDate ';
            $parameters['endDate'] = $search['endDate'];
        }
        
        if (isset($search['project'])) {
            $extraQuery .= ' AND time.project = :project ';
            $parameters['project'] = $search['project'];
        }

        $orderBy = " ORDER BY time.date DESC, time.startTime DESC ";
        
        $query = "
        SELECT time
        FROM BackendBundle:TimeTracking time
        WHERE time.user = :userId 
        AND time.endTime IS NOT NULL".$extraQuery.$orderBy;

        $consult = $em->createQuery($query);
        $consult->setParameters($parameters);

        return $consult->getResult();
    }

}
