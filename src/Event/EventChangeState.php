<?php

namespace App\Event;

use App\Entity\Event;
use App\Entity\State;
use App\Repository\EventRepository;
use App\Repository\StateRepository;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EventChangeState
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * Vérifier que les états (Ouverte, clôturée, activitée en cours et terminée) et les modifier en qu'à de changement de condition
     */
    public function changeState()
    {
        $em = $this->doctrine->getManager();

        //Récupérer tous les events ouverts.
        $stateRepo = $this->doctrine->getRepository(State::class);
        $stateOpen = $stateRepo->findOneBy(['name' => 'Ouverte']);
        $stateClose = $stateRepo->findOneBy(['name' => 'Clôturée']);
        dump($stateClose);
//        $stateObject = new State();
//        foreach ($stateClose as $key=>$value){
//            $stateObject->$key=$value;
//        }
        //dump($stateObject);

        //Passage de ouverts à clôturé
        $eventRepo = $this->doctrine->getRepository(Event::class);
        $events = $eventRepo->findBy(['state' => $stateOpen]);

        //nbInscrits=nbMaxInscrits ou date > dateClôture
        foreach ($events as $event) {
        $participantFull = $this->isFull($event);
        dump($participantFull);
            if (new DateTime() > $event->getInscriptionLimit() || $participantFull==true) {
                $event->setName('Essai2');
                $event->setState($stateClose);
                $em->persist($event);
            }
        }
        $em->flush();
        dump($events);

        //Passage de clôturé à ouverts
        //nbInscrits<nbMaxInscrits et date <= dateClôture


        return $events;
    }

    /*
     * takes an $event
     * returns a boolean
     * true if the number of participants is equal to the inscription limit
     */
    public function isFull($event)
    {
        $isFull = true;
        $maxParticipants = $event->getMaxParticipant();
        $numParticipants = count($event->getParticipants());

        if ($numParticipants < $maxParticipants) {
            $isFull = false;
        }

        return $isFull;
    }


}