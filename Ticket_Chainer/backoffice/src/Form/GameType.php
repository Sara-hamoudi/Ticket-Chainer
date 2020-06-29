<?php

namespace App\Form;

use App\Form\DataTransformer\GameStatusTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TicketChainer\ApiBundle\Model\Club;
use TicketChainer\ApiBundle\Model\Game;
use TicketChainer\ApiBundle\Model\Stadium;
use TicketChainer\ApiBundle\Repository\Core\RepositoryProvider;

class GameType extends AbstractType
{

    /**
     * @var RepositoryProvider
     */
    private $provider;


    public function __construct(RepositoryProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setAction($options['form_action'])
            ->add('opponent', ChoiceType::class, [
                'label' => 'Équipe adverse',
                'choices' => $this->getClubs(),
                'placeholder' => '',
                'choice_label' => 'name',
                'choice_value' => 'id',
                'required' => true,
            ])
            ->add('stadium', ChoiceType::class, [
                'label' => 'Stade',
                'choices' => $this->getStadiums(),
                'choice_label' => 'name',
                'choice_value' => 'id',
                'required' => true,
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
            ])
            ->add('date', DateTimeType::class, [
                'label' => 'Date & Heure',
                'widget' => 'single_text',
                'required' => true,
                'format' => 'dd/MM/yyyy HH:mm',
                'view_timezone' => 'Europe/Paris',
                'html5' => false
            ])
            ->add('matchday', ChoiceType::class, [
                'label' => 'Journée',
                'choices' => $this->getMatchDaysOptions()
            ])
            ->add('images', CollectionType::class, [
                'label' => 'Photos',
                'entry_type' => TextType::class,
                'allow_add' => true,
                'prototype' => true,
                'prototype_data' => 'New Tag Placeholder',
            ])
            ->add('status', CheckboxType::class, [
                'label' => 'Publier sur le site ?',
                'required' => false
            ])
            ->add('cancel', ResetType::class, [
                'label' => 'button.cancel'
            ])
            ->add('save', SubmitType::class, [
                'label' => 'button.save'
            ]);

        $builder->get('status')->addModelTransformer(new GameStatusTransformer());
    }

    private function getClubs()
    {
        $repository = $this->provider->getRepository(Club::class);
        return $repository->find();
    }

    private function getStadiums()
    {
        $repository = $this->provider->getRepository(Stadium::class);
        return $repository->find();
    }

    private function getMatchDaysOptions(): array
    {
        $options = [];
        for ($i = 1; $i <= 60; $i++) {
            $options[$i] = $i;
        }
        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
            'form_action' => null
        ]);
    }
}