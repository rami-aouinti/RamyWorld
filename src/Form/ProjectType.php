<?php

namespace App\Form;

use App\Entity\Project;
use App\Repository\TagRepository;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\TextEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('logo', FileType::class, [
                'label' => false,

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                'attr' => [
                    'class' => 'form-control dropzone',
                    'id' => 'dropzone'
                ],

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PNG file',
                    ])
                ],
            ])
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'projectName',
                    'placeholder' => 'Project Name'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => false
            ])
            ->add('active', CheckboxType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-check-input',
                    'id' => 'flexSwitchCheckDefault'
                ]
            ])
            ->add('start_date', null, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control datetimepicker',
                    'data_input' => true
                ]
            ])
            ->add('end_date', null, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control datetimepicker',
                    'data_input' => true
                ]
            ])
            ->add('file', FileType::class, [
                'label' => false,

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                'attr' => [
                    'class' => 'form-control dropzone',
                    'id' => 'dropzone'
                ],

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
            ->add('tag', null, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'multiple' => '',
                    'id' => 'exampleFormControlSelect1'
                ]
            ])
            ->add('team', null, [
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
