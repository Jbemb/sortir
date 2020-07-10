<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Entity\Event;
use App\Entity\Search;
use App\Entity\State;
use App\Entity\User;
use \DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function search(Search $search, User $user)
    {
        $qb = $this->createQueryBuilder('e')
            ->join('e.state', 's');


        $qb->andWhere('e.campus = :campus')
            ->setParameter('campus', $search->getCampus());

        if ($search->getKeywords() != '') {
            $qb->andWhere('e.name LIKE :keywords')
                ->setParameter('keywords', '%' . $search->getKeywords() . '%');
        }

        if (!is_null($search->getStartDate())) {
            $qb->andWhere('e.startDateTime > :startDate')
                ->orWhere('e.startDateTime = :startDate')
                ->setParameter('startDate', $search->getStartDate());
        }

        if (!is_null($search->getEndDate())) {
            $qb->andWhere('e.startDateTime < :endDate')
                ->orWhere('e.startDateTime = :endDate')
                ->setParameter('endDate', $search->getEndDate());
        }

        if ($search->isOrganiser()) {
            $organiser = 'e.organiser = :organiser';
        } else {
            $organiser = 'e.organiser NOT IN (:organiser)';
        }



        if ($search->isPassedEvent()) {
            $passed = 's.name = :passed';
        } else {
            $passed = 's.name NOT IN (:passed)';
        }

        $qb->andWhere($organiser /*. ' or ' . $passed*/);
        $qb->andWhere($passed);
        $qb->setParameter('organiser', $user);
        $qb->setParameter('passed', State::PASSED);


        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
