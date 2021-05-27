<?php


namespace LaFabrique\CSVMaker\Annotations;


use LaFabrique\CSVMaker\Metadata\CSVAnnotation;

class AtOrder implements CSVAnnotation
{

    public function handle($value)
    {
        return intval($value);
    }
}