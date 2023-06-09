<?php

use Phpro\SoapClient\CodeGenerator\Assembler;
use Phpro\SoapClient\CodeGenerator\Config\Config;
use Phpro\SoapClient\CodeGenerator\Rules;
use Phpro\SoapClient\Soap\DefaultEngineFactory;
use Soap\ExtSoapEngine\ExtSoapOptions;

return Config::create()
    ->setEngine($engine = DefaultEngineFactory::create(
        ExtSoapOptions::defaults('https://top.cbr.nl/WSapi/ReferenceDataService.svc?singleWsdl', [])
            ->disableWsdlCache()
    ))
    ->setTypeDestination('src/ReferenceData/Type')
    ->setTypeNamespace('MetMax\Cbr\ReferenceData\Type')
    ->setClientDestination('src/ReferenceData')
    ->setClientName('ReferenceDataClient')
    ->setClientNamespace('MetMax\Cbr\ReferenceData')
    ->setClassMapDestination('src/ReferenceData')
    ->setClassMapName('ReferenceDataClassmap')
    ->setClassMapNamespace('MetMax\Cbr\ReferenceData')
    ->addRule(new Rules\AssembleRule(new Assembler\GetterAssembler(new Assembler\GetterAssemblerOptions())))
    ->addRule(new Rules\AssembleRule(new Assembler\ImmutableSetterAssembler(
        new Assembler\ImmutableSetterAssemblerOptions()
    )))
    ->addRule(
        new Rules\IsRequestRule(
            $engine->getMetadata(),
            new Rules\MultiRule([
                new Rules\AssembleRule(new Assembler\RequestAssembler()),
                new Rules\AssembleRule(new Assembler\ConstructorAssembler(new Assembler\ConstructorAssemblerOptions())),
            ])
        )
    )
    ->addRule(
        new Rules\IsResultRule(
            $engine->getMetadata(),
            new Rules\MultiRule([
                new Rules\AssembleRule(new Assembler\ResultAssembler()),
            ])
        )
    );
