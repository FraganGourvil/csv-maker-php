<?php

namespace LaFabrique\CSVMaker\Annotations;

use LaFabrique\CSVMaker\Metadata\CSVAnnotation;

class AtName implements CSVAnnotation
{

    public function handle($value)
    {
        return ucwords($value);
    }
}