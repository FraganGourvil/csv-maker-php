<?php


namespace LaFabrique\CSVMaker\Annotations;


use LaFabrique\CSVMaker\Metadata\CSVAnnotation;

class AtPriority implements CSVAnnotation
{

    public function handle($value)
    {
        return intval($value);
    }
}