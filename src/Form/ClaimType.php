<?php

namespace App\Form;

use App\Entity\ClaimStatus;
use App\Entity\Claim;
use App\Form\DataTransformer\CommentTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;

;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class ClaimType extends AbstractType
{
    public function __construct(
        private readonly CommentTransformer $transformer,
        private Security $security,
    )
    {

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'help' => 'Заполните заголовок',
                'attr' => [
                    'class' => 'myclass'
                ]
            ])
            
            ->add('text', TextareaType::class, ['required' => false])

            ->add('comments', TextType::class, array (
                'label' => 'Tags',
                'required' => false,
            ))
            ;
            if($this->security->isGranted('ROLE_ADMIN')) {
                $builder->add('claimstatus', EntityType::class, [
                    // looks for choices from this entity
                    'class' => ClaimStatus::class,
                    //не обязательно выбирать категорию либо убрать уже существующую
                    'required' => false,
                    'empty_data' => '',
                    // uses the User.username property as the visible option string
                    'choice_label' => 'name',
                    'placeholder' => '-- выбор категории --'
                ]);
            }
            $builder->get('comments')
            ->addModelTransformer($this->transformer);
        ;


        // $builder
        // ->add('title')
        // ->add('text')
        // ->add('category', TextType::class, [
        //     // Опционально: убери 'required' и 'empty_data', чтобы разрешить пустое значение
        //     'required' => false,
        //     'empty_data' => null,
        //     // Опционально: укажи атрибуты для поля ввода
        //     'attr' => [
        //         'placeholder' => 'Введите название категории',
        //     ],
        // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Claim::class,
        ]);
    }
}
