<?php

namespace Plugin\CMBlog\Form\Type\Admin;

use Plugin\CMBlog\Entity\Blog;
use Plugin\CMBlog\Entity\Category;
use Plugin\CMBlog\Repository\BlogRepository;
use Plugin\CMBlog\Repository\CategoryRepository;
use Plugin\CMBlog\Form\Type\Admin\BlogStatusType;
use Plugin\CMBlog\Form\Validator\Hankaku;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class BlogType extends AbstractType
{
    /**
     * @var BlogRepository
     */
    protected $blogRepository;

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * BlogType constructor.
     *
     * @param BlogRepository $blogRepository
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(
        BlogRepository $blogRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->blogRepository = $blogRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title', TextType::class, [
            'required' => true,
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 200]),
            ],
        ])
        ->add('Category', ChoiceType::class, [
            'choice_label' => 'name',
            'multiple' => true,
            'mapped' => false,
            'expanded' => true,
            'choices' => $this->categoryRepository->getList(array()),
            'choice_value' => function (Category $Category = null) {
                return $Category ? $Category->getId() : null;
            },
        ])
        ->add('product_image', FileType::class, [
            'multiple' => true,
            'required' => false,
            'mapped' => false,
        ])
        
        // ->add('main_image', TextType::class, [
        //     'required' => false,
        //     'mapped' => false,
        // ])
        // ->add('image', FileType::class, [
        //     'mapped' => false,
        //     'required' => false,
        //     'constraints' => [
        //         new File([
        //             'maxSize' => '1024k',
        //             'mimeTypes' => [
        //                 'image/jpeg',
        //                 'image/gif',
        //                 'image/png',
        //                 'image/tiff'
        //             ],
        //             'mimeTypesMessage' => '有効な画像をアップロードして下さい'
        //         ])
        //     ]
        // ])

        // 画像
        ->add('images', CollectionType::class, [
            'entry_type' => HiddenType::class,
            'prototype' => true,
            'mapped' => false,
            'allow_add' => true,
            'allow_delete' => true,
        ])
        ->add('add_images', CollectionType::class, [
            'entry_type' => HiddenType::class,
            'prototype' => true,
            'mapped' => false,
            'allow_add' => true,
            'allow_delete' => true,
        ])
        ->add('delete_images', CollectionType::class, [
            'entry_type' => HiddenType::class,
            'prototype' => true,
            'mapped' => false,
            'allow_add' => true,
            'allow_delete' => true,
        ])
        ->add('return_link', HiddenType::class, [
            'mapped' => false,
        ])
        ->add('body', TextareaType::class, [
            'attr' => [
                'rows' => 20,
            ],
            'required' => false,
        ])
        ->add('author', TextType::class, [
            'required' => false,
            'constraints' => [
                new Length(['max' => 255]),
            ],
        ])
        ->add('description', TextType::class, [
            'required' => false,
            'constraints' => [
                new Length(['max' => 255]),
            ],
        ])
        ->add('keyword', TextType::class, [
            'required' => false,
            'constraints' => [
                new Length(['max' => 255]),
            ],
        ])
        ->add('robot', TextType::class, [
            'required' => false,
            'constraints' => [
                new Length(['max' => 255]),
            ],
        ])
        ->add('metatag', TextareaType::class, [
            'required' => false,
        ])
        ->add('release_date', DateType::class, [
            'required'  => false,
            'widget'    => 'single_text',
            'attr'      => array(
                'placeholder' => 'yyyy-MM-dd',
            ),
            'format'    => 'yyyy-MM-dd'
        ])
        ->add('status', BlogStatusType::class, [
            'constraints' => [
                new NotBlank(),
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'cmblog_admin_blog';
    }
}
