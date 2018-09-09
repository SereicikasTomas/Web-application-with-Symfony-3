<?php

namespace CarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class DefaultController extends Controller
{
    /**
     *@Route("/our-cars", name="offer")
     *'@Car/Default/index.html.twig'
     */
    public function indexAction(Request $request)
    {
        $carRepository = $this->getDoctrine()->getRepository('CarBundle:Car');
        $cars = $carRepository->findCarsWithDetails();
        // $cars = [
        //     ['make' => 'BMW', 'name' => 'X1'],
        //     ['make' => 'Fiat', 'name' => 'Croma'],
        //     ['make' => 'Audi', 'name' => 'Q7'],
        // ];
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

        if($form->isSubmitted() && $form->isValid()){
            die('form submitted');
        }
        return $this->render('CarBundle:Default:index.html.twig', 
            [  
                'cars' => $cars,
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @param $id
     * @Route("/car/{id}", name="show_car")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id) {
        $carRepository = $this->getDoctrine()->getRepository('CarBundle:Car');
        $car = $carRepository->findCarWithDetailsWithId($id);
        return $this->render('CarBundle:Default:show.html.twig', ['car' => $car]);
    }
}
