<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\DateSchedules;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateScheduleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('date', null, [
                'widget' => 'single_text',
            ])
            ->add('startedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('endedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('isOpen', CheckboxType::class, [
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DateSchedules::class,
        ]);
    }
}
