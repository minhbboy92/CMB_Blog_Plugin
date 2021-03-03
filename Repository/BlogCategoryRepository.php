<?php

namespace Plugin\CMBlog\Repository;

use Eccube\Doctrine\Query\Queries;
use Eccube\Util\StringUtil;
use Eccube\Repository\AbstractRepository;
use Plugin\CMBlog\Entity\BlogCategory;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * BlogCategoryRepository
 */
class BlogCategoryRepository extends AbstractRepository
{
    /**
     * BlogCategoryRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BlogCategory::class);
    }

}
