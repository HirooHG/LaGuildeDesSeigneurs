<?php

namespace App\Repository;

use App\Entity\Character;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Character>
 *
 * @method Character|null find($id, $lockMode = null, $lockVersion = null)
 * @method Character|null findOneBy(array $criteria, array $orderBy = null)
 * @method Character[]    findAll()
 * @method Character[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Character::class);
  }

  //    /**
  //     * @return Character[] Returns an array of Character objects
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
  public function findAll(): array
  {
    return $this->createQueryBuilder('Character')
      ->getQuery()
      ->getResult();
  }

  public function findOneByIdentifier($identifier): ?Character
  {
    return $this->createQueryBuilder('Character')
      ->andWhere('Character.identifier = :identifier')
      ->setParameter('identifier', $identifier)
      ->getQuery()
      ->getOneOrNullResult();
  }
}
