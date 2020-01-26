<?php

namespace App\Repository;

use App\Entity\Commit;
use App\Entity\Entry;
use App\Exception\NotFoundException;
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
    const DEFAULT_MAX_RESULTS = 1000;

    use Deletes;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commit::class);
    }

    /**
     * @param array $data
     *
     * @throws NotFoundException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @return Commit
     */
    public function create(array $data): Commit
    {
        $commit = new Commit();
        $commit->setBranch($data['branch']);
        $commit->setRepository(@$data['repository']);
        $commit->setDate(new \DateTime($data['date']));
        $commit->setSha(@$data['sha']);

        if (!empty($data['entry_id'])) {
            $entryRepository = $this->getEntityManager()->getRepository(Entry::class);
            $entry = $entryRepository->find($data['entry_id']);
            if (empty($entry)) {
                throw new NotFoundException('Could not find entry with ID '.$data['entry_id']);
            }

            $commit->setEntry($entry);
        }

        $this->getEntityManager()->persist($commit);
        $this->getEntityManager()->flush();

        return $commit;
    }

    /**
     * @param string $name
     *
     * @return array
     */
    public function findByBranch(string $name): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.branch= :val')
            ->setParameter('val', $name)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(self::DEFAULT_MAX_RESULTS)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $name
     *
     * @return array
     */
    public function findByRepository(string $name): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.repository= :val')
            ->setParameter('val', $name)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(self::DEFAULT_MAX_RESULTS)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param array $sha
     *
     * @return array
     */
    public function findBySha(array $sha): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.sha IN (:sha)')
            ->setParameter('sha', $sha)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(self::DEFAULT_MAX_RESULTS)
            ->getQuery()
            ->getResult();
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
