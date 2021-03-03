<?php

namespace Plugin\CMBlog\Form\Type\Admin;

use Plugin\CMBlog\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class CategoryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'plg.category.category_name',
                'attr' => [
                    'placeholder' => 'カテゴリ名',
                    'maxlength' => 100
                ]
            ])
            ->add('class', TextType::class, [
                'required' => false,
                'label' => 'plg.category.category_class_name',
                'attr' => [
                    'placeholder' => 'クラス名',
                    'maxlength' => 100
                ],
                'constraints' => [       
                    new Assert\Regex([ 
                        'pattern' => '/^[a-zA-Z0-9\-\_]+$/',
                        'message' => '半角英数字で入力してください',
                     ]),       
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
