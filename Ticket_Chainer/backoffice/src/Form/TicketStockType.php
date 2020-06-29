<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TicketChainer\ApiBundle\Model\TicketStock;

class TicketStockType extends AbstractType
{


    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setAction($options['form_action'])
            ->add('stockInitial', NumberType::class, ['label' => 'Quota de place pour la vente en ligne'])
            ->add('stock', NumberType::class, ['label' => 'Nombre de places restantes'])
            ->add('cancel', ResetType::class, [
                'label' => 'button.cancel'
            ])
            ->add('save', SubmitType::class, [
                'label' => 'button.save'
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TicketStock::class,
            'form_action' => null
        ]);
    }
}