<?php

namespace Plugin\CMBlog\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Category
 *
 * @ORM\Table(name="plg_blog_category")
 * @ORM\Entity(repositoryClass="Plugin\CMBlog\Repository\CategoryRepository")
 * @UniqueEntity("name")
 */
class Category
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="class", type="string", length=100, nullable=true)
     */
    private $class;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Plugin\CMBlog\Entity\BlogCategory", mappedBy="Category", fetch="EXTRA_LAZY")
     */
    private $BlogCategories;

    /**
     * Get id.
     * 
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get Name
     * 
     * @return int
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set Name
     * 
     * @param string $value
     *
     * @return $this;
     */
    public function setName($value)
    {
        $this->name = $value;
        return $this;
    }

    /**
     * Get Class
     * 
     * @return int
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set Class
     * 
     * @param string $value
     *
     * @return $this;
     */
    public function setClass($value)
    {
        $this->class = $value;
        return $this;
    }

    /**
     * Add blogCategory.
     *
     * @param \Plugin\CMBlog\Entity\BlogCategory $blogCategory
     *
     * @return Category
     */
    public function addBlogCategory(\Plugin\CMBlog\Entity\BlogCategory $blogCategory)
    {
        $this->BlogCategories[] = $blogCategory;

        return $this;
    }

    /**
     * Remove blogCategory.
     *
     * @param \Plugin\CMBlog\Entity\BlogCategory $blogCategory
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeBlogCategory(\Plugin\CMBlog\Entity\BlogCategory $blogCategory)
    {
        return $this->BlogCategories->removeElement($blogCategory);
    }

    /**
     * Get blogCategories.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBlogCategories()
    {
        return $this->BlogCategories;
    }
}
