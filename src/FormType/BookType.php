<?php

namespace App\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('author', TextType::class)
            ->add('description', TextareaType::class)
            ->add('publicationYear', IntegerType::class)
            ->add('isbn', IntegerType::class,[
                'constraints' => [

                ]
            ])
            ->add('cover', FileType::class, [
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '25Mi',
                        'extensions' => ['jpg', 'png']
                    ])
                ],
                'data_class' => null
            ])
            ->add('save', SubmitType::class)
        ;
    }
}