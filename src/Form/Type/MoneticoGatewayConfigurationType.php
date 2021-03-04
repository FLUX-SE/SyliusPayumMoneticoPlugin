<?php

declare(strict_types=1);

namespace FluxSE\SyliusPayumMoneticoPlugin\Form\Type;

use Ekyna\Component\Payum\Monetico\Api\Api;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

final class MoneticoGatewayConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mode', ChoiceType::class, [
                'expanded' => true,
                'label' => 'flux_se.sylius_payum_monetico.fields.mode.label',
                'choices' => [
                    'flux_se.sylius_payum_monetico.mode.production' => Api::MODE_PRODUCTION,
                    'flux_se.sylius_payum_monetico.mode.test' => Api::MODE_TEST,
                ],
            ])
            ->add('tpe', TextType::class, [
                'label' => 'flux_se.sylius_payum_monetico.fields.tpe.label',
                'help' => 'flux_se.sylius_payum_monetico.fields.tpe.help',
                'constraints' => [
                    new NotBlank([
                        'message' => 'flux_se.sylius_payum_monetico.tpe.not_blank',
                        'groups' => ['sylius'],
                    ]),
                    new Length([
                        'groups' => ['sylius'],
                        'min' => 7,
                        'max' => 7,
                    ]),
                ],
            ])
            ->add('key', TextType::class, [
                'label' => 'flux_se.sylius_payum_monetico.fields.key.label',
                'constraints' => [
                    new NotBlank([
                        'message' => 'flux_se.sylius_payum_monetico.key.not_blank',
                        'groups' => ['sylius'],
                    ]),
                    new Length([
                        'groups' => ['sylius'],
                        'max' => 40,
                    ]),
                ],
            ])
            ->add('company', TextType::class, [
                'label' => 'flux_se.sylius_payum_monetico.fields.company.label',
                'help' => 'flux_se.sylius_payum_monetico.fields.company.help',
                'constraints' => [
                    new NotBlank([
                        'message' => 'flux_se.sylius_payum_monetico.company.not_blank',
                        'groups' => ['sylius'],
                    ]),
                    new Length([
                        'groups' => ['sylius'],
                        'max' => 20,
                    ]),
                ],
            ])
        ;
    }
}
