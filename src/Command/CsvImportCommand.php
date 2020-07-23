<?php

namespace App\Command;

use App\Entity\Campus;
use App\Entity\User;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use League\Csv\Reader;

class CsvImportCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:import:csv';
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
    }

    protected function configure()
    {
        $this->setDescription('Commande pour importer des utilisateurs dans la base de donnée');
        $this->setHelp('Le pseudo et l\'adresse mail doivent être uniques.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $reader = Reader::createFromPath('%kernel.root_dir%/../public/csv/userImport4.csv');
        $results = $reader->fetchAssoc();

        foreach ($results as $row){
            //create a new user
            $user = new User();
            //hydrate user
            $role = explode(',', $row['roles']);
            $user->setUsername($row['username'])
                ->setRoles($role)
                ->setPassword($row['password'])
                ->setLastName($row['last_name'])
                ->setFirstName($row['first_name'])
                ->setTelephone($row['telephone'])
                ->setEmail($row['email'])
                ->setIsActive($row['is_active']);
            $this->em->persist($user);


            $campusRepo = $this->em->getRepository(Campus::class);
            //hydrate campus
            $campus = $campusRepo->find($row['campus_id']);
            $user->setCampus($campus);
        }
        $this->em->flush();


        $io->success('Les utilisateurs ont été importés.');

        return 0;
    }
}
