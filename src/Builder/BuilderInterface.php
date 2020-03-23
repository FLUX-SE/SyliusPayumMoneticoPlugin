<?php


namespace Prometee\SyliusPayumMoneticoPlugin\Builder;


interface BuilderInterface
{
    /**
     * @param $payload
     * @return mixed
     */
    public function build($payload);
}