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
        foreach ($campusList as $c) {
            $campus = new Campus();
            $campus->setName($c);

            $manager->persist($campus);
        }
        $manager->flush();

        $campusRepo = $manager->getRepository(Campus::class);
        $campus = $campusRepo->findAll();


        foreach ($campus as $c) {
            echo $c->getName() . "\n";
        }

        $users = [];
        for ($u = 0; $u < 100; $u++) {
            $user = new User();

            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);


            $count = 0;
            foreach ($users as $us) {
                if ($us->getLastName() == $user->getLastName() and mb_substr($us->getFirstName(), 0, 1) == mb_substr($user->getFirstName(), 0, 1)) {
                    $count++;
                }
            }


            $user->setUsername($this->setUsername($user, $count));
            $user->setEmail($this->setUsername($user, $count) . '2020@campus-eni.fr');
            $user->setCampus($campus[rand(0, count($campus) - 1)]);
            $user->setIsActive(true);
            $user->setTelephone($faker->phoneNumber);
            $user->setRoles(['ROLE_USER']);

            echo $user->getFirstName() . ' ' . $user->getLastName() . "\n";
            echo $user->getUsername() . "\n";

            $password = $this->encoder->encodePassword($user, 'toto');
            $user->setPassword($password);

            $manager->persist($user);
            $users[] = $user;

        }

        $manager->flush();
    }

    private function setUsername($user, $count): string
    {
        return $this->skip_accents(mb_substr($user->getFirstName(), 0, 1) . $user->getLastName() . ($count == 0 ? '' : $count));
    }

    private function skip_accents($str, $charset = 'utf-8')
    {

        $str = htmlentities($str, ENT_NOQUOTES, $charset);

        $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
        $str = preg_replace('#&[^;]+;#', '', $str);

        return $str;
    }
}
