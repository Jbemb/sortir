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

        //Récupérer tous les states.
        $stateRepo = $this->doctrine->getRepository(State::class);
        $stateOpen = $stateRepo->findOneBy(['name' => 'Ouverte']);
        $stateClose = $stateRepo->findOneBy(['name' => 'Clôturée']);
        $stateOnGoing = $stateRepo->findOneBy(['name'=>'Activité en cours']);
        $stateOver = $stateRepo->findOneBy(['name'=>'Passée']);


        //Passage de ouverts à clôturé
        $eventRepo = $this->doctrine->getRepository(Event::class);
        $eventsOpen = $eventRepo->findBy(['state' => $stateOpen]);

        //nbInscrits=nbMaxInscrits ou date > dateClôture
        foreach ($eventsOpen as $event) {
        $Full = $this->isFull($event);
            if (new DateTime() > $event->getInscriptionLimit() || $Full==true) {
                $event->setName('ouvert à clôturé');
                $event->setState($stateClose);
                $em->persist($event);
            }
        }
        $em->flush();


        //Passage de clôturé à ouverts
        $eventsClose = $eventRepo->findBy(['state'=>$stateClose]);
        //nbInscrits<nbMaxInscrits et date <= dateClôture
        foreach ($eventsClose as $event){
            $notFull = $this->isFull($event);
            if (new DateTime() <= $event->getInscriptionLimit() && $notFull==false){
                $event->setName('Clôturé à ouvert');
                $event->setState($stateOpen);
                $em->persist($event);
            }
        }
        $em->flush();

        //Passage de clôturée à activité en cours
        $eventsClosed = $eventRepo->findBy(['state'=>$stateClose]);
        foreach ($eventsClosed as $event){
           $over = $this->isOnGoing($event);
           if ($over==true){
               $event->setName('clôturée à activité en cours');
                $event->setState($stateOnGoing);
                $em->persist($event);
            }
        }
        $em->flush();

        //Passage de activité en cours à Activité terminée
        $eventsOnGoing = $eventRepo->findBy(['state'=>$stateOnGoing]);
        foreach ($eventsOnGoing as $event){
            $isFinished = $this->isFinished($event);
            if ($isFinished==true){
                $event->setName('activité en cours à Activité terminée');
                $event->setState($stateOver);
                $em->persist($event);
            }
        }
        $em->flush();

        return $eventsOnGoing;
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

    /*
     * takes an $event
     * returns a boolean
     * true if the current date is past the date of inscription
     */
    public function isPassedInscription($event)
    {
        $ispassed = true;
        $inscriptionDate = $event->getInscriptionLimit();
        $now = new \DateTime();

        if ($inscriptionDate > $now) {
            $isPassed = false;
        }

        return $isPassed;
    }
    /*
     * takes an $event
     * returns a boolean
     * true if the event is on going
     */
    public function isOnGoing($event)
    {
        $isOnGoing = false;
        // saved in database with seconds
        $start = $event->getStartDateTime();
        $duration = $event->getDuration();
        $dateInt = \DateInterval::createFromDateString($duration. 'minutes');
        $end = $start->add($dateInt);
        $now = new \DateTime();

        if ($start < $now || $now < $end) {
            $isOnGoing = true;
        }
        return $isOnGoing;
    }

    /*
    * takes an $event
    * returns a boolean
    * true if the event has started
    */
    public function hasStarted($event)
    {
        $hasStarted = true;
        $startTime = $event->getStartDateTime();
        $now = new \DateTime();

        if ($startTime < $now) {
            $hasStarted = false;
        }

        return $hasStarted;
    }

    /**
     * @param $event
     * @return bool
     * return true if the event is finished
     */
    public function isFinished($event)
    {
        $isFinished = true;
        $now = new \DateTime();
        $start = $event->getStartDateTime();
        $duration = $event->getDuration();
        $dateInt = \DateInterval::createFromDateString($duration. 'minutes');
        $endTime = $start->add($dateInt);

        if ($endTime>$now){
            $isFinished = false;
        }
        return $isFinished;
    }

}