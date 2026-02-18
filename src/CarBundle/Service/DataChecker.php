<?php


namespace CarBundle\Service;

use CarBundle\Entity\Car;
use Doctrine\ORM\EntityManagerInterface;


class DataChecker
{

    /** @var boolean */
    protected $requireImagesToPromoteCar;
    
    /** @var EntityManagerInterface */
    protected $entityManager;
    /**
     * DataChecker constructor.
     * 
     * @param EntityManagerInterface $entityManager
     * @param bool $requireImagesToPromoteCar
     */
    public function __construct(EntityManagerInterface $entityManager, $requireImagesToPromoteCar)
    {
        $this->entityManager = $entityManager;
        $this->requireImagesToPromoteCar = $requireImagesToPromoteCar;
    }

    public function checkCar(Car $car)
    {
        $promote = true;
        if ($this->requireImagesToPromoteCar) {
            $promote = false;
        }

        $car->setPromote($promote);
        $this->entityManager->persist($car);
        $this->entityManager->flush();

        return $promote;
    }
}
