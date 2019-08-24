<?php

namespace App\Repository;

use App\Entity\Commit;
use App\Repository\Traits\Deletes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Commit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commit[]    findAll()
 * @method Commit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommitRepository extends ServiceEntityRepository
{
    use Deletes;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commit::class);
    }

    public function create(array $data): Commit
    {
        $commit = new Commit();
        $commit->setBranch($data['branch']);
        $commit->setRepository($data['repository']);
        $commit->setCommits((int) $data['commits']);
        $commit->setDate(new \DateTime($data['date']));

        $this->getEntityManager()->persist($commit);
        $this->getEntityManager()->flush();

        return $commit;
    }

    /**
     * @param string $name
     * @return array
     */
    public function findByBranch(string $name): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.branch= :val')
            ->setParameter('val', $name)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param string $name
     * @return array
     */
    public function findByRepository(string $name): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.repository= :val')
            ->setParameter('val', $name)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Commit[] Returns an array of Commit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Commit
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
