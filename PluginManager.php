<?php

namespace Plugin\CMBlog;

use Doctrine\ORM\EntityManagerInterface;
use Eccube\Common\Constant;
use Eccube\Plugin\AbstractPluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
// Entity
use Eccube\Entity\Block;
use Eccube\Entity\Page;
use Eccube\Entity\PageLayout;
use Eccube\Entity\Master\DeviceType;
use Plugin\CMBlog\Entity\BlogStatus;
use Plugin\CMBlog\Entity\Config;
// Repository
use Eccube\Repository\BlockRepository;
use Eccube\Repository\LayoutRepository;
use Eccube\Repository\PageRepository;
use Eccube\Repository\PageLayoutRepository;
use Eccube\Repository\Master\DeviceTypeRepository;


class PluginManager extends AbstractPluginManager
{
    // プラグインのコンフィグ
    const CONFIGID = 1;
    const DISPLAYBLOCK = 4;
    const DISPLAYPAGE = 8;
    const IMAGEPATH = 'cm_blog/images';
    // ブロック
    const BLOCKNAME = "CMブログ";
    const BLOCKFILENAME = "cm_blog_block";
    const BLOCKFOLDERNAME = "/Block/";
    // ページ一覧
    const PAGELISTNAME = "CMブログ一覧ページ";
    const PAGELISTFILENAME = "web/list";
    const PAGELISTURL = "cm_blog_page_list";
    // ページ
    const PAGEDETAILNAME = "CMブログ詳細ページ";
    const PAGEDETAILFILENAME = "web/detail";
    const PAGEDETAILURL = "cm_blog_page_detail";

    /**
     * プラグイン有効時の処理
     *
     * @param $meta
     * @param ContainerInterface $container
     */
    public function enable(array $meta, ContainerInterface $container)
    {
        $em = $container->get('doctrine.orm.entity_manager');
        $em->getConnection()->beginTransaction();
        try {
            $Config = $this->initConfig($em);
            $ListPage = $this->initListPage($em, $container);
            $ListPageLayout = $this->initPageLayout($em, $container, $ListPage);
            $DetailPage = $this->initDetailPage($em, $container);
            $DetailPageLayout = $this->initPageLayout($em, $container, $DetailPage);
            $Block = $this->initBlock($em, $container);
            $this->initBlogStatus($em);
            $em->getConnection()->commit();
        } catch(Exception $e) {
            $em->getConnection()->rollback();
            // log error
        }
    }

    /**
     * プラグイン無効時の処理
     *
     * @param $meta
     * @param ContainerInterface $container
     */
    public function disable(array $meta, ContainerInterface $container)
    {
        // データを残す
        // レイアウトから削除する
        // ブロックを削除する
        // $em = $container->get('doctrine.orm.entity_manager');
        // $Block = $this->deleteBlock($em, $container);
        // コントローラを削除する
        // ページを削除する
    }

    /**
     * ブロックを追加
     * 
     * @param EntityManagerInterface $em
     * @param Model $obj
     */
    private function saveObject(EntityManagerInterface $em, $obj)
    {
        $em->persist($obj);
        $em->flush($obj);
        return $obj;
    }

    /**
     * テンプレート作成
     *
     * @param str $templateDir // 現在のテーマのテンプレートにファイルを配置するため現在のテーマのディレクトリを取得します
     * @param str $folder
     * @param str $filename
     */
    private function createTemplate($templateDir, $folder, $filename)
    {
        $sourceFile = __DIR__.'/Resource/template/'.$filename.'.twig';

        // コピー先にファイルがない場合のみファイルをコピーします
        $file = new Filesystem();
        if (!$file->exists($templateDir.$folder.$filename.'.twig')) {
            // app/template配下へブロックのtwigファイルを配置
            $file->copy($sourceFile, $templateDir.$folder.$filename.'.twig');
        }
    }

    /**
     * ページを追加
     *
     * @param EntityManagerInterface $em
     * @param ContainerInterface $container
     */
    protected function initListPage(
        EntityManagerInterface $em,
        ContainerInterface $container)
    {
        $Page = $container->get(PageRepository::class)->findOneBy(
            [
                'name'       => self::PAGELISTNAME,
                'file_name'  => self::PAGELISTFILENAME,
                'url'        => self::PAGELISTURL,
                'edit_type'  => PAGE::EDIT_TYPE_DEFAULT
            ]
        );
        if ($Page) {
            // 既にあるデータと何もしない
            $this->createTemplate(
                $container->getParameter('eccube_theme_front_dir'),
                '/', self::PAGELISTFILENAME);
            return $Page;
        }
        // 新しいデータを作成する
        $Page = new Page();
        $Page
            ->setUrl(self::PAGELISTURL)
            ->setFileName(self::PAGELISTFILENAME)
            ->setName(self::PAGELISTNAME)
            ->setEditType(PAGE::EDIT_TYPE_DEFAULT);
        $this->createTemplate(
            $container->getParameter('eccube_theme_front_dir'),
            '/', self::PAGELISTFILENAME);
        return $this->saveObject($em, $Page);
    }

    /**
     * ページ一覧Layoutを追加
     *
     * @param EntityManagerInterface $em
     * @param ContainerInterface $container
     */
    protected function initPageLayout(
        EntityManagerInterface $em,
        ContainerInterface $container,
        Page $page)
    {
        $PageLayout = $container->get(PageLayoutRepository::class)->findOneBy(
            [
                'page_id'    => $page->getId()
            ]
        );
        if ($PageLayout) {
            // 既にあるデータと何もしない
            return $PageLayout;
        }
        // 新しいデータを作成する
        $Layout = $container->get(LayoutRepository::class)->find(2);
        $PageLayout = new PageLayout();
        $PageLayout->setPageId($page->getId());
        $PageLayout->setPage($page);
        $PageLayout->setLayoutId(2);
        $PageLayout->setLayout($Layout);
        $PageLayout->setSortNo($page->getId());
        return $this->saveObject($em, $PageLayout);
    }

    /**
     * ページ詳細を追加
     *
     * @param EntityManagerInterface $em
     * @param ContainerInterface $container
     */
    protected function initDetailPage(
        EntityManagerInterface $em,
        ContainerInterface $container)
    {
        $Page = $container->get(PageRepository::class)->findOneBy(
            [
                'name'       => self::PAGEDETAILNAME,
                'file_name'  => self::PAGEDETAILFILENAME,
                'url'        => self::PAGEDETAILURL,
                'edit_type'  => PAGE::EDIT_TYPE_DEFAULT
            ]
        );
        if ($Page) {
            // 既にあるデータと何もしない
            $this->createTemplate(
                $container->getParameter('eccube_theme_front_dir'),
                '/', self::PAGEDETAILFILENAME);
            return $Page;
        }
        // 新しいデータを作成する
        $Page = new Page();
        $Page
            ->setUrl(self::PAGEDETAILURL)
            ->setFileName(self::PAGEDETAILFILENAME)
            ->setName(self::PAGEDETAILNAME)
            ->setEditType(PAGE::EDIT_TYPE_DEFAULT);
        $this->createTemplate(
            $container->getParameter('eccube_theme_front_dir'),
            '/', self::PAGEDETAILFILENAME);
        return $this->saveObject($em, $Page);
    }

    /**
     * ブロックを追加
     *
     * @param EntityManagerInterface $em
     * @param ContainerInterface $container
     */
    protected function initBlock(
        EntityManagerInterface $em,
        ContainerInterface $container)
    {
        $DeviceType = $container->get(DeviceTypeRepository::class)
            ->find(DeviceType::DEVICE_TYPE_PC);

        $Block = $container->get(BlockRepository::class)->findOneBy(
            [
                'name'       => self::BLOCKNAME,
                'file_name'  => self::BLOCKFILENAME,
                'DeviceType' => $DeviceType
            ]
        );
        if ($Block) {
            // 既にあるデータと何もしない
            $this->createTemplate(
                $container->getParameter('eccube_theme_front_dir'),
                self::BLOCKFOLDERNAME,
                self::BLOCKFILENAME);
            return $Block;
        }
        // 新しいデータを作成する
        $Block = new Block();
        $Block->setName(self::BLOCKNAME);
        $Block->setFileName(self::BLOCKFILENAME);
        $Block->setUseController(Constant::DISABLED);
        $Block->setDeletable(Constant::DISABLED);
        $Block->setDeviceType($DeviceType);
        $this->createTemplate(
            $container->getParameter('eccube_theme_front_dir'),
            self::BLOCKFOLDERNAME,
            self::BLOCKFILENAME);
        return $this->saveObject($em, $Block);
    }

    /**
     * プラグイン設定を追加
     *
     * @param EntityManagerInterface $em
     */
    protected function initConfig(EntityManagerInterface $em)
    {
        $Config = $em->find(Config::class, self::CONFIGID);
        if ($Config) {
            // 既にあるデータをリセットする
            return $this->saveObject($em, $Config);
        }
        // 新しいデータを作成する
        $Config = new Config();
        $Config->setDisplayBlock(self::DISPLAYBLOCK);
        $Config->setDisplayPage(self::DISPLAYPAGE);
        $Config->setImagePath(self::IMAGEPATH);
        return $this->saveObject($em, $Config);
    }

    /**
     * ブログステータスマスタ
     *
     * @param EntityManagerInterface $em
     */
    protected function initBlogStatus(EntityManagerInterface $em)
    {
        $sql = "INSERT into plg_blog_mtb_status VALUES(:id, :name, :sort, :type)";
        $stmt = $em->getConnection()->prepare($sql);

        $params = array(
            "id"    => 1,
            "name"  => "公開",
            "sort"  => 0,
            "type"  => "blogstatus"
        );
        $stmt->execute($params);

        $params = array(
            "id"    => 2,
            "name"  => "非公開",
            "sort"  => 1,
            "type"  => "blogstatus"
        );
        $stmt->execute($params);

        $params = array(
            "id"    => 3,
            "name"  => "廃止",
            "sort"  => 2,
            "type"  => "blogstatus"
        );
        $stmt->execute($params);
    }

    /**
     * ブロックを削除する
     *
     * @param EntityManagerInterface $em
     * @param ContainerInterface $container
     */
    protected function deleteBlock(
        EntityManagerInterface $em,
        ContainerInterface $container)
    {
        $DeviceType = $container->get(DeviceTypeRepository::class)
            ->find(DeviceType::DEVICE_TYPE_PC);

        $Block = $container->get(BlockRepository::class)->findOneBy(
            [
                'name'       => self::BLOCKNAME,
                'file_name'  => self::BLOCKFILENAME,
                'DeviceType' => $DeviceType,
            ]
        );
        if ($Block) {
            $em->remove($Block);
            $em->flush();
        }
    }
}
