<?php

namespace App\Repository;

use App\Entity\Trajets;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trajets>
 *
 * @method Trajets|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trajets|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trajets[]    findAll()
 * @method Trajets[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrajetsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trajets::class);
    }

    public function save(Trajets $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Trajets $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Trajets[] Returns an array of Trajets objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Trajets
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function findAllByVille(): array
{
    $qb = $this->createQueryBuilder('t')
        ->leftJoin('t.villedepart', 'vd')->addSelect('vd')
        ->leftJoin('t.villearrive', 'va')->addSelect('va');

    $result = $qb->getQuery()->getResult();

    return $result;
}





// public function getAllTrajetsWithVilles(string $villedepart, string $villearrive)
// {
//     $qb = $this->createQueryBuilder('t');
//     $qb->select('IDENTITY(t.villedepart) as villedepart_id', 'IDENTITY(t.villearrive) as villearrive_id', 't.datetotravel')
//        ->from('App\Entity\Trajets', 't')  // add root entity alias
//        ->leftJoin('t.villedepart', 'vd')
//        ->addSelect('vd')
//        ->leftJoin('t.villearrive', 'va')
//        ->addSelect('va')
//        ->leftJoin('t.conducteurs', 'c')
//        ->addSelect('c')
//        ->where('vd.villenom = :ville_depart')
//        ->andWhere('va.villenom = :ville_arrive')
//        ->setParameter('ville_depart', $villedepart)
//        ->setParameter('ville_arrive', $villearrive);

//     $query = $qb->getQuery();
//     $results = $query->getResult();

//     return $results;
// }



// public function getAllTrajetsByVilles(string $villedepart, string $villearrive)
// {
//     $qb = $this->createQueryBuilder('t');
//     $qb->select('IDENTITY(t.villedepart) as villedepart_id', 'IDENTITY(t.villearrive) as villearrive_id', 't.datetotravel')
//     ->from('App\Entity\Trajets', 't')  // add root entity alias
//     ->leftJoin('t.villedepart', 'vd')
//     ->addSelect('vd')
//     ->leftJoin('t.villearrive', 'va')
//     ->addSelect('va')
//     ->where('vd.villenom = :ville_depart')
//     ->andWhere('va.villenom = :ville_arrive')
//     ->setParameter('ville_depart', $villedepart)
//     ->setParameter('ville_arrive', $villearrive)
//     ->leftJoin('t.conducteurs', 'c')
//     ->addSelect('c');


//     $query = $qb->getQuery();
//     $results = $query->getResult();

//     return $results;
// }





// public function findByVilles(string $villedepart, string $villearrive): array
// {
//     return $this->createQueryBuilder('t')
//         ->select('t.id', 'vd.nomville as villedepart', 'va.nomville as villearrive', 't.datetotravel')
//         ->leftJoin('t.villedepart', 'vd')
//         ->leftJoin('t.villearrive', 'va')
//         ->andWhere('vd.nomville = :villedepart')
//         ->andWhere('va.nomville = :villearrive')
//         ->setParameter('villedepart', $villedepart)
//         ->setParameter('villearrive', $villearrive)
//         ->getQuery()
//         ->getResult();
// }

public function findByVillesAndDate(string $villeD, string $villeA, \DateTimeInterface $dateT)
{
    $qb = $this->createQueryBuilder('t');
    $qb->select('t')
        ->leftJoin('t.villedepart', 'vd')
        ->addSelect('vd')
        ->leftJoin('t.villearrive', 'va')
        ->addSelect('va')
        ->where('vd.villenom = :ville_depart')
        ->andWhere('va.villenom = :ville_arrive')
        ->andWhere('t.datetotravel = :date_t')
        ->setParameter('ville_depart', $villeD)
        ->setParameter('ville_arrive', $villeA)
        ->setParameter('date_t', $dateT);

    $query = $qb->getQuery();
    $results = $query->getResult();

    return $results;
}






}
