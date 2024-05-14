<?php

namespace App\Repository;

use App\Entity\Building;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Building>
 *
 * @method Building|null find($id, $lockMode = null, $lockVersion = null)
 * @method Building|null findOneBy(array $criteria, array $orderBy = null)
 * @method Building[]    findAll()
 * @method Building[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BuildingRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Building::class);
  }

  // findAll()
  public function findAll(): array
  {
    return $this->createQueryBuilder('Building')
      ->getQuery()
      ->getResult();
  }
  public function findOneByIdentifier(string $identifier): ?Building
  {
    return $this->createQueryBuilder('p')
      ->select('p', 'c')
      ->leftJoin('p.characters', 'c')
      ->where('p.identifier = :identifier')
      ->setParameter('identifier', $identifier)
      ->getQuery()
      ->getOneOrNullResult();
  }
}
