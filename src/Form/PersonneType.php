<?php

namespace App\Form;

use App\Entity\Personne;
use App\Entity\Hobbie;
use App\Entity\Profile;
use App\Entity\Job;
use Doctrine\DBAL\Types\StringType;
use Doctrine\ORM\EntityRepository;
use PhpParser\Node\Scalar\StringTest;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PersonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('age')
            ->add('updatedAt')
            ->add('createdAt')
            ->add('profile', EntityType::class, [
                'expanded'=> false,
                'class' => Profile::class,
                'required'=> false,
                'multiple' => false,
                'attr'=> [
                    'class'=>'select2'
                ],
                'required'=> false,
            ])
            ->add('hobbies', EntityType::class, [
                'expanded'=> false,
                'required'=> false,
                'class' => Hobbie::class,
                'multiple' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('h')
                        ->orderBy('h.designation', 'ASC');
                },
                'choice_label' => 'designation',
                'attr'=> [
                    'class'=>'select2'
                ]
            ])
            ->add('job', EntityType::class, [
                'expanded'=> false,
                'required'=> false,
                'multiple' => false,
                'class' => Job::class,
                'attr'=> [
                    'class'=>'select2'
                ]
            ])
            ->add('photo', FileType::class, [
                'label' => 'Votre photo',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                        ],
                        'maxSizeMessage'=> 'SVP chargez une image de taille inférieure ou égale à 1Mo',
                        'mimeTypesMessage' => 'SVP chargez une image valide',
                    ])
                ],
            ])
            ->add('editer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Personne::class,
        ]);
    }
}
