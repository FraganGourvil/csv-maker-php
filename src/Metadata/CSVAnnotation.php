<?php

namespace LaFabrique\CSVMaker\Metadata;

interface CSVAnnotation
{
    public function handle($value);
}