<?php


namespace LaFabrique\CSVMaker\Annotations;


use LaFabrique\CSVMaker\Metadata\CSVAnnotation;

class AtOn implements CSVAnnotation
{

    public function handle($value)
    {
        return $this->extract($value);

    }

    private function extract($conditions) {

        $conditions = preg_replace('/[\s+\[\]]/', '', $conditions);

        $conditions = explode(',', $conditions);

        $conditionsArray = array_map(function ($haystack) {

            return explode('=', $haystack);

        }, $conditions);

        foreach ($conditionsArray as $key => $toFormat) {

            $format = $toFormat[0];

            if(strtolower($toFormat[0]) == "null") {

                $format = "NULL";

            }

            if(is_numeric($toFormat[0])) {

                $format = intval($toFormat[0]);

            }

            if(is_bool($toFormat[0])) {

                $format = boolval($toFormat[0]);

            }

            $conditionsArray[$key][0] = $format;

        }

        return $conditionsArray;

    }

}