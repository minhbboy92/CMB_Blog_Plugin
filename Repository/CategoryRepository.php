<?php

namespace Plugin\CMBlog\Repository;

use Eccube\Doctrine\Query\Queries;
use Eccube\Repository\AbstractRepository;
use Eccube\Util\StringUtil;
use Plugin\CMBlog\Entity\Category;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * CategoryRepository
 */
class CategoryRepository extends AbstractRepository
{
    /**
     * CategoryRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(
        RegistryInterface $registry,
        Queries $queries)
    {
        parent::__construct($registry, Category::class);
        $this->queries = $queries;
    }

    /**
     * @param int $id
     *
     * @return null|Category
     */
    public function get($id = 1)
    {
        return $this->find($id);
    }

    /**
     * @param array $searchData
     *
     * @return null|Category
     */
    public function getList($searchData)
    {
        $qb = $this->createQueryBuilder('o')
            ->select('o');

        // id
        if (isset($searchData['id']) && StringUtil::isNotBlank($searchData['id'])) {
            $qb
                ->andWhere('o.id = :id')
                ->setParameter('id', $searchData['id']);
        }

        // カテゴリ
        if (isset($searchData['name']) && StringUtil::isNotBlank($searchData['name'])) {
            $qb
                ->andWhere('o.name LIKE :name')
                ->setParameter('name', '%'.$searchData['name'].'%');
        }

        // Order By
        $qb->orderBy('o.id', 'ASC');

        return $qb->getQuery()->getResult();
    }


     /**
     * @param array $searchData
     *
     * @return null|Category
     */
    public function getFrontCategoryList()
    {
        $qb = $this->createQueryBuilder('o')
        // ブログがあるカテゴリしか表示しません
            ->innerJoin('o.BlogCategories', 'bct')
            ->select('o');

        // Order By
        $qb->orderBy('o.id', 'ASC');

        return $qb->getQuery()->getResult();
    }


    /**
     * get query builder.
     *
     * @param  array $searchData
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilderBySearchData($searchData)
    {
        $qb = $this->createQueryBuilder('o')->select('o');

        // id
        if (isset($searchData['id']) && StringUtil::isNotBlank($searchData['id'])) {
            $qb
                ->andWhere('o.id = :id')
                ->setParameter('id', $searchData['id']);
        }

        // カテゴリ
        if (isset($searchData['name']) && StringUtil::isNotBlank($searchData['name'])) {
            $qb
                ->andWhere('o.name LIKE :name')
                ->setParameter('name', '%'.$searchData['name'].'%');
        }

        // Order By
        $qb->orderBy('o.id', 'ASC');

        return $this->queries->customize('Category.getQueryBuilderBySearchData', $qb, $searchData);
    }
}
