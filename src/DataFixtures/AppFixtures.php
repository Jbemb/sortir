<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        $campusList = ['Rennes', 'Nantes', 'Niort', 'Quimper'];
        foreach ($campusList as $c){
            $campus = new Campus();
            $campus->setName($c);

            echo $c ."\n";

            $manager->persist($campus);
        }
        $manager->flush();

        $campusRepo = $manager->getRepository(Campus::class);
        $campus = $campusRepo->findAll();


        foreach ($campus as $c){
            echo $c->getName() . "\n";
        }

        for ($u = 0; $u < 30; $u++){
            $user = new User();

            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);
            $user->setEmail(strtolower(substr($user->getFirstName(),0,1) . $user->getLastName() . '2020@campus-eni.fr'));
            $user->setUsername(strtolower(substr($user->getFirstName(),0,1) . $user->getLastName()));
            $user->setCampus($campus[rand(0,count($campus) - 1)]);
            $user->setIsActive(true);
            $user->setTelephone($faker->phoneNumber);
            $user->setRoles(['ROLE_USER']);

            $password = $this->encoder->encodePassword($user, 'toto');
            $user->setPassword($password);

            $manager->persist($user);

            echo $user->getFirstName() . ' ' . $user->getLastName() . "\n";
        }

        $manager->flush();
    }
}
