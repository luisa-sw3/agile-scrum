<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Util\Util;

/**
 * Sprint
 * @author Cesar Giraldo <cesargiraldo1108@gmail.com> 23/12/2015
 * @ORM\Table(name="sprint")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Sprint {

    use Consecutive;

    /**
     * @ORM\Id
     * @ORM\Column(name="sprint_id", type="string", length=36)
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * Nombre del Sprint
     * @ORM\Column(name="sprint_name", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * Descripcion general del sprint
     * @ORM\Column(name="sprint_description", type="text", nullable=true)
     */
    protected $description;

    /**
     * Fecha de iniciación del sprint
     * @ORM\Column(name="sprint_start_date", type="datetime", nullable=false)
     * @Assert\NotBlank()
     */
    protected $startDate;

    /**
     * Fecha estimada para la finalizacion del sprint
     * @ORM\Column(name="sprint_end_date", type="datetime", nullable=true)
     */
    protected $estimatedDate;

    /**
     * Proyecto al que pertenece el Sprint
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="sprint_project_id", referencedColumnName="proj_id")
     */
    protected $project;
    
    /**
     * Usuario quien realiza la creación del sprint
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="sprint_user_owner", referencedColumnName="user_id")
     */
    protected $userOwner;
    
    /**
     * Boolean que permite saber si durante el Sprint se van a trabajar los fines
     * de semana (Sabados y Domingos)
     * @ORM\Column(name="sprint_is_working_weekends", type="boolean", nullable=true)
     */
    protected $isWorkingWeekends;
    
    /**
     * Fecha de creacion del sprint en el sistema
     * @ORM\Column(name="sprint_creation_date", type="datetime", nullable=true)
     */
    protected $creationDate;
    
    function getId() {
        return $this->id;
    }

    
    function getName() {
        return $this->name;
    }

    function getDescription() {
        return $this->description;
    }

    function getStartDate() {
        return $this->startDate;
    }

    function getEstimatedDate() {
        return $this->estimatedDate;
    }

    function getProject() {
        return $this->project;
    }

    function getUserOwner() {
        return $this->userOwner;
    }

    function getCreationDate() {
        return $this->creationDate;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setStartDate($startDate) {
        $this->startDate = $startDate;
    }

    function setEstimatedDate($estimatedDate) {
        $this->estimatedDate = $estimatedDate;
    }

    function setProject($project) {
        $this->project = $project;
    }

    function setUserOwner($userOwner) {
        $this->userOwner = $userOwner;
    }

    function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
    }

    function getIsWorkingWeekends() {
        return $this->isWorkingWeekends;
    }

    function setIsWorkingWeekends($isWorkingWeekends) {
        $this->isWorkingWeekends = $isWorkingWeekends;
    }

    public function __toString() {
        return $this->getName();
    }

    /**
     * Set Page initial status before persisting
     * @ORM\PrePersist
     */
    public function setDefaults() {
        if ($this->getCreationDate() === null) {
            $this->setCreationDate(Util::getCurrentDate());
        }
    }

}
