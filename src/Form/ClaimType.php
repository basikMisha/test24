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
                ],
                'label' => 'Заголовок',
            ])
            
            ->add('text', TextareaType::class, [
                'required' => false,
                'label' => 'Описание',
            ])

            ->add('comments', TextareaType::class, array (
                'label' => 'Комментарии',
                'required' => false,
            ))
            ;
            if($this->security->isGranted('ROLE_ADMIN') || $this->security->isGranted('ROLE_MANAGER')) {
                $builder->add('claimstatus', EntityType::class, [
                    'class' => ClaimStatus::class,
                    'required' => false,
                    'empty_data' => '',
                    'choice_label' => 'name',
                    'label' => 'Статус',
                    'placeholder' => '-- выбор статуса --'
                ]);
            }
            $builder->get('comments')
            ->addModelTransformer($this->transformer);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Claim::class,
        ]);
    }
}
