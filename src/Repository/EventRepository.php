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
            ->join('e.state', 's')
            ->addSelect('s')
            ->join('e.participants', 'p')
            ->addSelect('p')
        ;

        $qb->andWhere('e.campus = :campus')
            ->setParameter('campus', $search->getCampus());

        if ($search->getKeywords() != '') {
            $qb->andWhere('e.name LIKE :keywords')
                ->setParameter('keywords', '%' . $search->getKeywords() . '%');
        }

        if (!is_null($search->getStartDate())) {
            $qb->andWhere('e.startDateTime > :startDate or e.startDateTime = :startDate')
//                ->orWhere('e.startDateTime = :startDate')
                ->setParameter('startDate', $search->getStartDate());
        }

        if (!is_null($search->getEndDate())) {
            $qb->andWhere('e.startDateTime < :endDate or e.startDateTime = :endDate')
//                ->orWhere('e.startDateTime = :endDate')
                ->setParameter('endDate', $search->getEndDate());
        }

        if ($search->isOrganiser()) {
            $qb->andWhere('e.organiser = :organiser');
            $qb->setParameter('organiser', $user);
        }

        if ($search->isPassedEvent()) {
            $qb->andWhere('s.name = :passed');
            $qb->setParameter('passed', State::PASSED);
        }

        if ($search->isSignedUp()) {
            $qb->andWhere('p = :signedUpUser');
            $qb->setParameter('signedUpUser', $user);
        }

        if ($search->isNotSignedUp()) {
            $eventsToExclude = $this->createQueryBuilder('e')
                ->join('e.participants', 'p')
                ->where('p = :signedUpUser')
                ->setParameter('signedUpUser', $user)
                ->getQuery()
                ->getResult()
            ;
            $qb->andWhere('e NOT IN (:eventsToExclude)')
                ->setParameter('eventsToExclude', $eventsToExclude);
        }




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
