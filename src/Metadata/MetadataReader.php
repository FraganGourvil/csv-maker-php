<?php

namespace LaFabrique\CSVMaker\Metadata;

use LaFabrique\CSVMaker\Annotations\AtName;
use LaFabrique\CSVMaker\Annotations\AtOn;
use LaFabrique\CSVMaker\Annotations\AtOrder;
use LaFabrique\CSVMaker\Annotations\AtPriority;

class MetadataReader
{

    protected $annotations = [];

    protected $extracted = [];

    public function __construct()
    {

        $this->annotations['Column'] = new AtName();
        $this->annotations['Order'] = new AtOrder();
        $this->annotations['On'] = new AtOn();
        $this->annotations['Priority'] = new AtPriority();

    }

    public function read($class) {

        $reflect = new \ReflectionClass($class);

        foreach ($reflect->getProperties(\ReflectionProperty::IS_PUBLIC) as $requiredProperty) {

            $clean = $this->cleanDoc($requiredProperty->getDocComment());

            $extracted = $this->extractAnnotations($clean);

            $finalProcess = [];

            foreach ($extracted as $annotation => $value) {

                $key = array_search($this->annotations[$annotation], $this->annotations);

                $finalProcess[$key] = $this->annotations[$annotation]->handle($value);

            }

            $this->extracted[$requiredProperty->getName()] = $finalProcess;

        }

        return $this->extracted;

    }

    private function cleanDoc($string) {

        return preg_replace('/[\s+(?=[()]*\\)]|[\*\/]|[^)]*$/', '', $string);

    }

    private function extractAnnotations($clean) {

        $readed = explode('@', $clean);

        $extract = [];

        foreach ($readed as $entry) {

            if(preg_match('#[^0-9a-z-A-Z]#', $entry))
            {
                $process = explode('(', $entry);

                if(array_key_exists($process[0], $this->annotations)) {

                    $extract[$process[0]] = preg_replace('/\s\s+|\)/', '', $process[1]);

                }

            }

        }

        return $extract;

    }

}