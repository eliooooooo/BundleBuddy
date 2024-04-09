<?php

namespace App\Repository;

use App\Entity\Package;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Package>
 *
 * @method Package|null find($id, $lockMode = null, $lockVersion = null)
 * @method Package|null findOneBy(array $criteria, array $orderBy = null)
 * @method Package[]    findAll()
 * @method Package[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PackageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Package::class);
    }

    //    /**
    //     * @return Package[] Returns an array of Package objects
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

    //    public function findOneBySomeField($value): ?Package
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    // public function getPackageByCategory($category)
    // {
    //     $query = $this->createQueryBuilder('p');

    //     if ($category != 'all') {
    //         $query->join('p.category', 'c')
    //             ->andWhere('c.id = :category')
    //             ->setParameter('category', $category);
    //     }

    //     return $query->getQuery()->getResult();
    // }

    public function getPackageByFilters($category, $language)
    {
        $query = $this->createQueryBuilder('p');

        if ($category != 'all') {
            $query->join('p.category', 'c')
                ->andWhere('c.id = :category')
                ->setParameter('category', $category);
        }
        if ($language != 'all') {
            $query->andWhere('p.language = :language')
                ->setParameter('language', $language);
        }

        return $query->getQuery()->getResult();
    }

    public function findAllLanguages()
    {
        $query = $this->createQueryBuilder('p')
            ->select('p.language')
            ->distinct();

        return $query->getQuery()->getResult();
    }

    // public function getPackageByLanguage($lang)
    // {
    //     $query = $this->createQueryBuilder('p');

    //     if ($lang != 'all') {
    //         $query->andWhere('p.language = :lang')
    //             ->setParameter('lang', $lang);
    //     }

    //     return $query->getQuery()->getResult();
    // }
}
