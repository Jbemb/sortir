<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Event;
use App\Entity\Place;
use App\Entity\User;
use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
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
        $faker = Factory::create('fr_FR');

        /*
         * Campus
         */
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

        /*
        * Cities
        */
        $cityList = ['Chicago', 'Rennes', 'Nantes', 'Seoul', 'Kiev', 'Bloomer'];
        $zipCodeList = [60007, 35000, 44000, 100011, 01001, 54724];
        for ($x = 0; $x < count($cityList); $x++) {
            $city = new City();
            $city->setName($cityList[$x]);
            $city->setPostalCode($zipCodeList[$x]);
            $manager->persist($city);
        }
        $manager->flush();

        $cityRepo = $manager->getRepository(City::class);
        $cities = $cityRepo->findAll();

        /*
         * Places
         */
        $placeNames = ['plage', 'bar', 'bowling', 'ballade', 'chez Lulu', 'Réu CDA', 'quartier du coin', 'au bar de la rue qui tourne'];


        foreach ($placeNames as $placeName) {
            $place = new Place();

            $place->setCity($cities[rand(0, count($cities) - 1)]);
            $place->setLatitude($faker->latitude);
            $place->setLongitude($faker->longitude);
            $place->setName($placeName);
            $place->setStreet($faker->streetAddress);
            $manager->persist($place);
        }
        $manager->flush();

        $placeRepo = $manager->getRepository(Place::class);
        $places = $placeRepo->findAll();


        /*
         * UsernamesUs
         */

        $usernameUs= ['fred', 'leslie', 'janet', 'andrea'];

        foreach ($usernameUs as $username){
            $user = new User();

            $user->setUsername($username);
            $user->setFirstName($username);
            $user->setLastName($username);
            $user->setTelephone($faker->phoneNumber);
            $user->setEmail($username . '2020@campus-eni.fr');
            $user->setCampus($campus[rand(0, count($campus) - 1)]);
            $user->setIsActive(true);
            $user->setRoles(['ROLE_USER']);

            $password = $this->encoder->encodePassword($user, 'toto');
            $user->setPassword($password);

            $manager->persist($user);

        }
        $manager->flush();



        /*
         * Users
         */

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

        $userRepo = $manager->getRepository(User::class);
        $users = $userRepo->findAll();

        /*
         * States
         */
        $stateList = ['Créée', 'Ouverte', 'Clôturée', 'Activité en cours', 'Passée', 'Annulée'];
        foreach ($stateList as $s) {
            $state = new State();
            $state->setName($s);

            $manager->persist($state);
        }
        $manager->flush();

        $stateRepo = $manager->getRepository(State::class);
        $states = $stateRepo->findAll();


        /*
         * Events
         */

        for ($i = 0; $i < 50; $i++) {
            $event = new Event();
            $event->setName($faker->sentence(5,true));
            $event->setStartDateTime($faker->dateTimeBetween('+10 hours', '+150 days'));
            $date = $event->getStartDateTime();
            $event->setDuration($faker->numberBetween(30, 300));
            $event->setInscriptionLimit(date_sub($date, date_interval_create_from_date_string(rand(1, 9) . " hours")));
            $event->setMaxParticipant(rand(4, 20));
            $event->setEventInfo($faker->sentence(30, true));
            $event->setPlace($places[rand(0, count($places) - 1)]);
            $event->setCampus($campus[rand(0, count($campus) - 1)]);
            $event->setOrganiser($users[rand(0, count($users) - 1)]);
            $event->setState($states[rand(0, count($states) - 1)]);

            $nbParticpants = rand(0, $event->getMaxParticipant());
            for ($j = 0; $j < $nbParticpants; $j++) {
                $event->addParticipant($users[rand(0, count($users) - 1)]);
            }

            if ($event->getState()->getName() == 'Annulée'){
                $event->setReasonDelete($faker->sentence(30, true));
            }

            $manager->persist($event);
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
