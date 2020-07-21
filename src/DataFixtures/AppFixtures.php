<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Event;
use App\Entity\Place;
use App\Entity\User;
use App\Entity\State;
use App\Event\EventChangeState;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    private $ecs;

    public function __construct(UserPasswordEncoderInterface $encoder, EventChangeState $ecs)
    {
        $this->encoder = $encoder;
        $this->ecs = $ecs;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        echo 'Création des fixtures ;)';

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
        $placeNames = ['Petite Plage', 'Bar de la Soif', 'Bowling', 'Chemin en bois', 'Chez Lulu', 'Réu CDA', 'Quartier du Coin',
            'Bar de la rue qui tourne', 'Grant Park', 'PMU', 'Camping du Nord', 'Elephant', 'Place de la Republic', 'Creperie de Corentin',
            'McDonalds', 'Taco Bell', 'Beaulieu', 'Cleunay', 'Chez Mamie', 'The White House', 'The Blue House', 'Le Pont des Espions',
            'Main Street', 'Willis Tower', 'ENI', 'Place des Lices', 'Lac du bois'];


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

        $usernameUs = ['fred', 'leslie', 'janet', 'andrea'];

        foreach ($usernameUs as $username) {
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
        $stateList = [State::ONGOING, State::CREATED, State::CANCELED, State::CLOSED, State::OPENED, State::PASSED];
        foreach ($stateList as $s) {
            $state = new State();
            $state->setName($s);

            $manager->persist($state);
        }
        $manager->flush();

        $stateRepo = $manager->getRepository(State::class);
        $states = [
            $stateRepo->findOneBy(['name' => State::CANCELED]),
            $stateRepo->findOneBy(['name' => State::CREATED]),
            $stateRepo->findOneBy(['name' => State::OPENED])
        ];

        /*
         * Events
         */
        $eventNames = ['Natation', 'Job Dating', 'Speeding Dating', 'Balade à Vélo', 'Balade', 'Bar Hop', 'Concert de Celine',
            'Spectacle', 'Raclette', 'Apéro', 'Pot de départ', 'Bal', 'Picnic', 'Beer Pong', 'Soirée Déguisée', 'Basketball', 'Yoga',
            'Tennis', 'Presentation de Projet', 'Tournoi de petanque', 'Pizza Party', 'Mud Wrestling', 'Axe Throwing', 'Soutenance de Stage',
            'LAN', 'Pair Programming', 'Study Session'];


        for ($i = 0; $i < 50; $i++) {
            $event = new Event();
            $event->setName($eventNames[rand(0, count($eventNames) - 1)]);
            $event->setStartDateTime($faker->dateTimeBetween('-40 days', '+150 days'));
            $date = $event->getStartDateTime();
            $event->setDuration($faker->numberBetween(30, 300));
            $event->setInscriptionLimit(date_sub($date, date_interval_create_from_date_string(rand(1, 120) . " hours")));
            $event->setMaxParticipant(rand(4, 20));
            $event->setEventInfo($faker->sentence(30, true));
            $event->setPlace($places[rand(0, count($places) - 1)]);
            $event->setOrganiser($users[rand(0, count($users) - 1)]);
            $event->setCampus($event->getOrganiser()->getCampus());
            //check if date is passed and label it passed
            if ($this->ecs->isFinished($event)) {
                $passed = $stateRepo->findOneBy(['name' => State::PASSED]);
                $event->setState($passed);
            } //else check if it has started and label and label ongoing
            else if ($this->ecs->hasStarted($event)) {
                $onGoing = $stateRepo->findOneBy(['name' => State::ONGOING]);
                $event->setState($onGoing);
            } //else if inscription date is passed it is closed
            else if ($this->ecs->isPassedInscription($event)) {
                $closed = $stateRepo->findOneBy(['name' => State::CLOSED]);
                $event->setState($closed);
            }//else it is random
            else {
                $index = $faker->biasedNumberBetween($min = 0, $max = count($states) - 1, $function = 'sqrt');
            $event->setState($states[$index]);
        }

        $nbParticpants = rand(0, $event->getMaxParticipant());
        for ($j = 0; $j < $nbParticpants; $j++) {
            $event->addParticipant($users[rand(0, count($users) - 1)]);
        }

            if ($event->getState()->getName() == 'Annulée') {
                $event->setReasonDelete($faker->sentence(30, true));
            }


            $manager->persist($event);
        }

        $manager->flush();

        $this->ecs->archiveEvents();
        $this->ecs->changeState();
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
