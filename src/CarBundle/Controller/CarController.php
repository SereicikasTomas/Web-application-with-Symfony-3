<?php

namespace CarBundle\Controller;

use CarBundle\Entity\Car;
use CarBundle\Form\CarType;
use CarBundle\Service\DataChecker;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class CarController extends AbstractController
{
    /**
     * Lists all car entities.
     */
    public function indexAction(ManagerRegistry $doctrine)
    {
        $cars = $doctrine->getRepository(Car::class)->findAll();

        return $this->render('car/admin/index.html.twig', [
            'cars' => $cars,
        ]);
    }

    /**
     * Promote a car
     *
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function promoteAction(int $id, DataChecker $dataChecker, ManagerRegistry $doctrine)
    {
        $car = $doctrine->getRepository(Car::class)->find($id);

        if (!$car) {
            throw $this->createNotFoundException('Car not found.');
        }

        $result = $dataChecker->checkCar($car);
        if ($result) {
            $this->addFlash('success', 'Car promoted');
        } else {
            $this->addFlash('warning', 'Car not applicable');
        }

        return $this->redirectToRoute('car_index');
    }

    /**
     * Creates a new car entity.
     */
    public function newAction(Request $request, EntityManagerInterface $entityManager)
    {
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($car);
            $entityManager->flush();

            return $this->redirectToRoute('car_show', ['id' => $car->getId()]);
        }

        return $this->render('car/admin/new.html.twig', [
            'car' => $car,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a car entity.
     */
    public function showAction(int $id, ManagerRegistry $doctrine)
    {
        $car = $this->findCarOr404($id, $doctrine);
        $deleteForm = $this->createDeleteForm($car);

        return $this->render('car/admin/show.html.twig', [
            'car' => $car,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing car entity.
     */
    public function editAction(
        Request $request,
        int $id,
        ManagerRegistry $doctrine,
        EntityManagerInterface $entityManager
    )
    {
        $car = $this->findCarOr404($id, $doctrine);
        $deleteForm = $this->createDeleteForm($car);
        $editForm = $this->createForm(CarType::class, $car);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('car_edit', ['id' => $car->getId()]);
        }

        return $this->render('car/admin/edit.html.twig', [
            'car' => $car,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a car entity.
     */
    public function deleteAction(
        Request $request,
        int $id,
        ManagerRegistry $doctrine,
        EntityManagerInterface $entityManager
    )
    {
        $car = $this->findCarOr404($id, $doctrine);
        $form = $this->createDeleteForm($car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->remove($car);
            $entityManager->flush();
        }

        return $this->redirectToRoute('car_index');
    }

    /**
     * Creates a form to delete a car entity.
     *
     * @param Car $car The car entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Car $car): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('car_delete', ['id' => $car->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @param int $id
     *
     * @return Car
     */
    private function findCarOr404(int $id, ManagerRegistry $doctrine): Car
    {
        $car = $doctrine->getRepository(Car::class)->find($id);

        if (!$car) {
            throw $this->createNotFoundException('Car not found.');
        }

        return $car;
    }
}
