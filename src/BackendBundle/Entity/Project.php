<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Util\Util;

/**
 * Project
 * @author Cesar Giraldo <cesargiraldo1108@gmail.com> 23/12/2015
 * @ORM\Table(name="project")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Project {

    use Consecutive;

    /**
     * @ORM\Id
     * @ORM\Column(name="proj_id", type="string", length=36)
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * Nombre del Proyecto
     * @ORM\Column(name="proj_name", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * Descripcion general del proyecto
     * @ORM\Column(name="proj_description", type="text", nullable=true)
     */
    protected $description;

    /**
     * Fecha de creacion del proyecto en el sistema
     * @ORM\Column(name="proj_creation_date", type="datetime", nullable=true)
     */
    protected $creationDate;

    /**
     * Fecha de iniciación del proyecto
     * @ORM\Column(name="proj_start_date", type="datetime", nullable=false)
     * @Assert\NotBlank()
     */
    protected $startDate;

    /**
     * Fecha estimada para la finalizacion del proyecto
     * @ORM\Column(name="proj_estimated_date", type="datetime", nullable=true)
     */
    protected $estimatedDate;

    /**
     * Usuario quien realiza la creación del proyecto
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="proj_user_owner", referencedColumnName="user_id")
     */
    protected $userOwner;
    
    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getDescription() {
        return $this->description;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function getCreationDate() {
        return $this->creationDate;
    }

    function getStartDate() {
        return $this->startDate;
    }

    function getEstimatedDate() {
        return $this->estimatedDate;
    }

    function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
    }

    function setStartDate($startDate) {
        $this->startDate = $startDate;
    }

    function setEstimatedDate($estimatedDate) {
        $this->estimatedDate = $estimatedDate;
    }
    
    function getUserOwner() {
        return $this->userOwner;
    }

    function setUserOwner(User $userOwner) {
        $this->userOwner = $userOwner;
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
