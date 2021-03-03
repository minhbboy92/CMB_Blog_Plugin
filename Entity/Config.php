<?php

namespace Plugin\CMBlog\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Config
 *
 * @ORM\Table(name="plg_blog_config")
 * @ORM\Entity(repositoryClass="Plugin\CMBlog\Repository\ConfigRepository")
 */
class Config
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
     * @var int
     *
     * @ORM\Column(name="display_block", type="smallint", options={"unsigned":true, "default":4})
     */
    private $display_block;

    /**
     * @var int
     *
     * @ORM\Column(name="display_page", type="smallint", options={"unsigned":true, "default":8})
     */
    private $display_page;

    /**
     * @var string
     *
     * @ORM\Column(name="image_path", type="string")
     */
    private $image_path;

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
     * Get Display Block
     * 
     * @return int
     */
    public function getDisplayBlock()
    {
        return $this->display_block;
    }

    /**
     * Set Display Block
     * 
     * @param int $value
     *
     * @return $this;
     */
    public function setDisplayBlock($value)
    {
        $this->display_block = $value;
        return $this;
    }

    /**
     * Get Display Page
     * 
     * @return int
     */
    public function getDisplayPage()
    {
        return $this->display_page;
    }

    /**
     * Set Display Page
     * 
     * @param int $value
     *
     * @return $this;
     */
    public function setDisplayPage($value)
    {
        $this->display_page = $value;
        return $this;
    }

    /**
     * Get Image path
     * 
     * @return string
     */
    public function getImagePath()
    {
        return $this->image_path;
    }

    /**
     * Set Image path
     * 
     * @param string $value
     *
     * @return $this;
     */
    public function setImagePath($value)
    {
        $this->image_path = $value;
        return $this;
    }

}
