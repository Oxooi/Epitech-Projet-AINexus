<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Discussions;
use App\Repository\CategoriesRepository;
use PhpParser\Parser\Multiple;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Image;

class CreateDiscussionFormType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('title', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Enter the discussion title',
                ],
            ])
            ->add('category', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'name',
                'label' => 'Categorie :',
                'query_builder' => function (CategoriesRepository $cr) {
                    return $cr
                        ->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
            ])
            ->add('images', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new All(
                        new Image([
                            'maxWidth' => 1280,
                            'maxWidthMessage' => 'L\'image doit faire {{ max_width }} pixels de large maximum'
                        ])
                    )
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Type your thoughts',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Discussions::class,
        ]);
    }
}
