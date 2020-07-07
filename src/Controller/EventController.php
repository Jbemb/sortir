<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\StateRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class EventController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/sortie/ajouter", name="event_add", methods={"GET", "Post"})
     *
     */
    public function add(Request $request, EntityManagerInterface $em, UserRepository $userRepo, StateRepository $stateRepo)
    {
        $user = $userRepo->findOneBy(['username' => $this->security->getUser()->getUsername()]);
        $event = new Event();
        $event->setOrganiser($user);
        $event->setCampus($user->getCampus());
        $eventForm = $this->createForm(EventType::class, $event);

        $eventForm -> handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()){
            $state= new State;

            if($eventForm->get('saveAndAdd')->isClicked()){
                $state = $stateRepo->findOneBy(['name' => 'Ouverte']);

            }elseif ($eventForm->get('save')->isClicked()){
                $state = $stateRepo->findOneBy(['name' => 'Créée']);
            }
            $event->setState($state);
            $em->persist($event);
            $em->flush();
            $this->addFlash("success", "Sortie enregistréé");
            return $this->redirectToRoute("event_post", ["id" => $event->getId()]);

        }

        return $this->render('event/add.html.twig', [
            'eventForm' => $eventForm->createView()
        ]);
    }
}
