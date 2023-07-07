<?php

namespace App\Repository;

use App\Entity\EstimatePrestation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EstimatePrestation>
 *
 * @method EstimatePrestation|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstimatePrestation|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstimatePrestation[]    findAll()
 * @method EstimatePrestation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstimatePrestationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EstimatePrestation::class);
    }

    public function save(EstimatePrestation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EstimatePrestation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return EstimatePrestation[] Returns an array of EstimatePrestation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EstimatePrestation
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
