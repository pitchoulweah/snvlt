<?php

namespace App\Repository;

use App\Entity\Personne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Personne>
 *
 * @method Personne|null find($id, $lockMode = null, $lockVersion = null)
 * @method Personne|null findOneBy(array $criteria, array $orderBy = null)
 * @method Personne[]    findAll()
 * @method Personne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Personne::class);
    }

    public function save(Personne $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Personne $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Personne[] Returns an array of Personne objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function findPersonneByAgeInterval($agemin, $agemax)
    {
       $qb = $this->createQueryBuilder('p');
        $this->addInterval($qb ,$agemin, $agemax);
         return   $qb->getQuery()->getResult();
   }
    public function statsPersonneByAgeInterval($agemin, $agemax)
    {
        $qb = $this->createQueryBuilder('p')
                    ->select('avg(p.age) as age_moyen, count(p.id) as nombrePersonne');
        $this->addInterval($qb ,$agemin, $agemax);


        return $qb->getQuery()
                    ->getScalarResult();
    }

    private function addInterval(QueryBuilder $qb, $ageMin, $ageMax){
        $qb->andWhere('p.age >= :agemin and  p.age <= :agemax')
            ->setParameters(['agemin'=> $ageMin, 'agemax'=> $ageMax],);
    }
}
