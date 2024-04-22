<?php

namespace App\Repository;

use App\Entity\Claim;
use App\Filter\ClaimFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Claim>
 *
 * @method Comments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comments[]    findAll()
 * @method Comments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClaimRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Claim::class);
    }

    public function getComments(): array
    {
        return $this->createQueryBuilder('c')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult();
    }

    public function findByCommentFilter(ClaimFilter $commentFilter)
    {
        $claims = $this->createQueryBuilder('c')->where('1 = 1');

        if($commentFilter->getUser()) {
            $claims
                ->andWhere('c.user = :user')
                ->setParameter('user', $commentFilter->getUser());
        }

        if($commentFilter->getTitle()) {
            $claims
                ->andWhere('c.title LIKE :title')
                ->setParameter('title', '%' . $commentFilter->getTitle() . '%');
        }
        return $claims->getQuery()->getResult();
    }

    //    /**
    //     * @return Comments[] Returns an array of Comments objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Comments
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
