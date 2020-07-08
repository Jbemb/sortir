<?php

namespace App\Event;

use App\Entity\Event;
use App\Entity\State;
use App\Repository\EventRepository;
use App\Repository\StateRepository;
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
        //Récupérer tous les events ouverts.
        $stateRepo = $this->doctrine->getRepository(State::class);
        $stateOpen = $stateRepo->findBy(['name' => 'Ouverte']);
        $stateClose = $stateRepo->findBy(['name' => 'Clôturée']);
        $eventRepo = $this->doctrine->getRepository(Event::class);
        $events = $eventRepo->findBy(['state' => $stateOpen]);


        //Passage de ouverts à clôturé
        //nbInscrits=nbMaxInscrits ou date > dateClôture
        $em = $this->doctrine->getManager();

        foreach ($events as $event){
            if ( getdate() > $event->getInscriptionLimit()){
                $event->setName('Essai2');


                //$event->setState($stateClose);

            }
/*            $em->persist($events);
            $em->flush();*/

        }

        //Passage de clôturé à ouverts
        //nbInscrits<nbMaxInscrits et date <= dateClôture


        return $events;
    }





}