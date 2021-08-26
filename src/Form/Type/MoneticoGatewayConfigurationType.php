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
    static $protocoles = [
        null => 'flux_se.sylius_payum_monetico.protocole_type.classic',
        '1euro' => 'flux_se.sylius_payum_monetico.protocole_type.1euro',
        '3xcb' => 'flux_se.sylius_payum_monetico.protocole_type.3xcb',
        '4xcb' => 'flux_se.sylius_payum_monetico.protocole_type.4xcb',
        'paypal' => 'flux_se.sylius_payum_monetico.protocole_type.paypal',
        'lyfpay' => 'flux_se.sylius_payum_monetico.protocole_type.lyfpay'
    ];

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
            ->add('protocol', ChoiceType::class, [
                'label' => 'flux_se.sylius_payum_monetico.fields.payment_protocol.label',
                'expanded' => true,
                'multiple' => false,
                'choices' => array_flip(self::$protocoles),
            ]);
    }
}
