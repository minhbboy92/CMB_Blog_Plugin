<?php

namespace Plugin\CMBlog;

use Eccube\Common\EccubeNav;

class Nav implements EccubeNav
{
    /**
     * @return array
     */
    public static function getNav()
    {
        return [
            'plugin_CMBlog' => [
                'name' => 'ブログ',
                'icon' => 'fa-file-text',
                'children' => [
                    'cm_blog_admin_blog' => [
                        'name' => 'ブログ一覧',
                        'url' => 'cm_blog_admin_blog',
                    ],
                    'cm_blog_admin_blog_create' => [
                        'name' => 'ブログ登録',
                        'url' => 'cm_blog_admin_blog_create',
                    ],
                    'cm_blog_admin_cat' => [
                        'name' => 'カテゴリ一覧',
                        'url' => 'cm_blog_admin_cat',
                    ],
                    'cm_blog_admin_cat_create' => [
                        'name' => 'カテゴリ登録',
                        'url' => 'cm_blog_admin_cat_create',
                    ]
                ]
            ]
        ];
    }
}
