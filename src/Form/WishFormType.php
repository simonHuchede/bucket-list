<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Wish;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WishFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',null,[
                "label"=>"titre : ",
                "attr"=>['autofocus'=>true]
            ])
            ->add('description',null, [
                "label"=>"description : "
            ])
            ->add('author',null,[
                "label"=>"auteur : ",

            ])
            ->add('category',EntityType::class,[
               "class"=>Category::class,
                "choice_label"=>"name",
                "label"=>"categorie : "

                ])
           // ->add('isPublished')
            //->add('dateCreated')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
        ]);
    }
}
