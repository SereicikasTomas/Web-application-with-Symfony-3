<?php

namespace CarBundle\Command;

use CarBundle\Entity\Car;
use CarBundle\Service\DataChecker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AbcCheckCarsCommand extends Command
{
    /** @var DataChecker */
    protected $carChecker;
    /** @var EntityManagerInterface */
    protected $manager;

    /**
     * AbcCheckCarsCommand constructor.
     * 
     * @param DataChecker $carChecker
     * @param EntityManagerInterface $manager
     */
    public function __construct(DataChecker $carChecker, EntityManagerInterface $manager)
    {
        $this->carChecker = $carChecker;
        $this->manager = $manager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('abc:check-cars')
            ->setDescription('...')
            ->addArgument('format', InputArgument::OPTIONAL, 'Progress format')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $carRepository = $this->manager->getRepository(Car::class);
        $cars = $carRepository->findAll();
        $bar = new ProgressBar($output, count($cars));

        $argument = $input->getArgument('format');
        if ($argument !== null) {
            $bar->setFormat($argument);
        }

        $bar->start();
        foreach ($cars as $car) {
            $this->carChecker->checkCar($car);
            sleep(1);
            $bar->advance();
        }
        $bar->finish();

        return 0;
    }

}
