<?php

namespace Prometee\SyliusPayumMoneticoPlugin\Form\Type;

use Ekyna\Component\Payum\Monetico\Api\Api;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

final class MoneticoGatewayConfigurationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mode', ChoiceType::class, [
                'choices' => [
                    'prometee.monetico.mode.production' => Api::MODE_PRODUCTION,
                    'prometee.monetico.mode.test' => Api::MODE_TEST
                ],
                'label' => 'prometee.monetico.fields.mode',
            ])
            ->add('bank', ChoiceType::class, [
                'choices' => [
                    'prometee.monetico.banque.cic' => Api::BANK_CIC,
                    'prometee.monetico.banque.cm' => Api::BANK_CM,
                    'prometee.monetico.banque.obc' => Api::BANK_OBC
                ],
                'label' => 'prometee.monetico.fields.bank',
            ])
            ->add('tpe', TextType::class, [
                'label' => 'prometee.monetico.fields.tpe',
                'constraints' => [
                    new NotBlank([
                        'message' => 'prometee.monetico.tpe.not_blank',
                        'groups' => ['sylius']
                    ])
                ],
            ])
            ->add('key', TextType::class, [
                'label' => 'prometee.monetico.fields.key',
                'constraints' => [
                    new NotBlank([
                        'message' => 'prometee.monetico.key.not_blank',
                        'groups' => ['sylius']
                    ])
                ],
            ])
            ->add('company', TextType::class, [
                'label' => 'prometee.monetico.fields.company',
                'constraints' => [
                    new NotBlank([
                        'message' => 'prometee.monetico.company.not_blank',
                        'groups' => ['sylius']
                    ])
                ],
            ])
            /*->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $data = $event->getData();
                $data['payum.http_client'] = '@prometee.monetico.bridge.monetico_bridge';
                $event->setData($data);
            })*/
        ;
    }
}