<?php

namespace App\Form;

use App\Entity\Chantier;
use App\Entity\Pointage;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

class PointageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateTimeType::class,
                [
                    'widget' => 'choice',
                    'attr' => [
                        'class' => 'form-control datepicker',
                        'placeholder' => 'Choissisez une date',
                    ],
                    'html5' => false,
                    'constraints' => [
                        new Assert\Callback([
                            'callback' => [$this, 'validateDate'],
                            'payload' => []
                        ]),
                    ],
                ])
            ->add('duree', IntegerType::class, [
                'attr' => ['min' => 1],
                'label' => 'Durée en heure',
                'constraints' => [
                    //new LessThanOrEqual(8),
                    new Assert\Callback([
                        'callback' => [$this, 'validateWeeklyDuration'],
                        'payload' => [],
                    ]),
                ],
            ])
            ->add('utilisateur', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'matricule',
                'label' => 'Matricule Utilisateur'
            ])
            ->add('chantier', EntityType::class, [
                'class' => Chantier::class,
                'choice_label' => 'nom',
            ]);
    }

    public function validateWeeklyDuration($value, ExecutionContextInterface $context)
    {
        $pointage = $context->getRoot()->getData();

        if(!$pointage instanceof Pointage) return;

        $totalDuration = $pointage->getUtilisateur()->calculateTotalDurationForWeek(
            $pointage->getDate()->format('Y'),
            $pointage->getDate()->format('W')
        );

        $totalDuration += $pointage->getDuree();

        if ($totalDuration > 35) {
            $context->buildViolation('La durée hebdomadaire maximale de pointage est dépassée (35 heures).')
                ->atPath('duree')
                ->addViolation();
        }
    }

    /**
     * @param $value
     * @param ExecutionContextInterface $context
     * @return void
     */
    public function validateDate($value, ExecutionContextInterface $context)
    {
        $pointage = $context->getRoot()->getData();

        if (!$pointage instanceof Pointage) return;

        if ($value < $pointage->getChantier()->getStartDate()) {
            $context->buildViolation("La date de pointage ne peut pas être antérieure à la date de début du chantier.")
                ->atPath("date")
                ->addViolation();
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pointage::class,
        ]);
    }
}
