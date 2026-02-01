<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
 
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        // Doctrine : ce repository = Article
        parent::__construct($registry, Article::class);
    }

    // Pagination
    public function findPaginated(int $page = 1, int $limit = 6): array
    { 
        $total = $this->count([]);

        // On récupère seulement les articles de la page demandée
        $offset = ($page - 1) * $limit;

        $items = $this->createQueryBuilder('a')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        // Nombre total de pages
        $pages = (int) ceil($total / $limit);

        // On renvoie tout au controlleur
        return [
            'items' => $items,   // les articles à afficher
            'total' => $total,   // nombre total d'articles
            'page'  => $page,    // la page actuelle
            'pages' => $pages,   // nombre total de pages
            'limit' => $limit,   // limite nombre d'articles par page
        ];
    }
}


    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

