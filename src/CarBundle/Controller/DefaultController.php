<?php

namespace CarBundle\Controller;

use CarBundle\Entity\Car;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class DefaultController extends AbstractController
{
    public function indexAction(Request $request, ManagerRegistry $doctrine)
    {
        $carRepository = $doctrine->getRepository(Car::class);

        $form = $this->createFormBuilder()
            ->setMethod('GET')
            ->add('search', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 2])
                ]
            ])
            ->getForm();

        $form->handleRequest($request);

        $searchTerm = null;
        if ($form->isSubmitted() && $form->isValid()) {
            $searchTerm = $form->get('search')->getData();
        }

        $cars = $carRepository->findCarsWithDetails($searchTerm);

        return $this->render('car/default/index.html.twig', [
            'cars' => $cars,
            'form' => $form->createView()
        ]);
    }

    public function showAction(int $id, ManagerRegistry $doctrine)
    {
        $carRepository = $doctrine->getRepository(Car::class);
        $car = $carRepository->findCarWithDetailsWithId($id);

        if (!$car) {
            throw $this->createNotFoundException('Car not found.');
        }

        return $this->render('car/default/show.html.twig', ['car' => $car]);
    }
}
