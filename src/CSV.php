<?php

namespace LaFabrique\CSVMaker;

use LaFabrique\CSVMaker\Exceptions\MissingProperties;
use LaFabrique\CSVMaker\Metadata\MetadataReader;
use ReflectionClass;
use ReflectionProperty;

abstract class CSV
{

    protected $_csvName;

    protected $_encodage;

    private $_columns = [];

    private $_reader;

    public function __construct($name = null, $encodage = CSVEncodage::UTF8, $reader = null)
    {

        $this->_csvName = (!is_null($name)) ? $name : 'CSV_' . date('dmY_His');

        $this->_encodage = $encodage;

        $this->_reader = (is_null($reader)) ? new MetadataReader() : $reader;

        $this->fillColumns();

    }

    public function create()
    {

        $sorted = $this->sortColumns();

    }

    public function getFirstLine() {

        $line = [];

        foreach ($this->_columns as $column) {

            $line[] = $this->{$column}->getAlias();

        }

        return $line;

    }

    public function valuesToArray() {

        $values = [];

        $reflect = new ReflectionClass($this);

        $properties = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);

        for ($i = 0; $i < sizeof($this->{$properties[0]->name}->getValues()); $i++) {

            $tmp = [];

            foreach ($properties as $property) {

                $tmp[] = $this->{$property->name}->getValues()[$i];

            }

            $values[] = $tmp;

        }

        return $values;

    }

    /**
     * @return array
     */
    public function getLines()
    {
        return null;
    }

    /**
     * @param array $lines
     */
    public function addLine(Line $line)
    {

        try {

            $this->processLine($line);

        } catch (\Exception $e) {


        }

    }

    public function removeLine($line)
    {

        //TODO : Remove

    }

    private function processLine(Line $line)
    {

        $line = $line->write();

        if (!$this->matchLineColumns($line)) {

            throw new \Exception("Un erreur c'est produite lors du remplissage du CSV");

        }

        return $line;

    }

    private function matchLineColumns(Line $line)
    {

        foreach ($this->_columns as $column) {

            if (!$line->existProperty($column)) {

                throw new MissingProperties("La propriété {$this->{$column}->getName()} est manquante dans les propiétés de la ligne.");

            }

            $this->{$column}->addValue($line->{$column});

        }

        return true;

    }

    private function fillColumns()
    {

        $reflect = new ReflectionClass($this);

        $properties = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);

        $index = 0;

        foreach ($this->_reader->read($this) as $metadata) {

            $column = new Column();

            foreach ($metadata as $method => $value) {

                $toCall = 'set' . ucfirst($method);

                $column->$toCall($value);

            }

            $column->setName($properties[$index]->name);

            $this->{$properties[$index]->name} = $column;

            $this->_columns[] = $properties[$index]->name;

            $index++;

        }

    }

    private function sortColumns()
    {

        $sorted = [];

        $toSort = $this->_columns;

        while (sizeof($toSort) > 0) {

            $index = $this->{$toSort[0]};

            foreach ($toSort as $column) {

                if ($this->{$column}->getOrder() < $index->getOrder()) {

                    $index = $this->{$column};

                } elseif ($this->{$column}->getOrder() === $index->getOrder()) {

                    if ($this->{$column}->getPriority() > $index->getPriority()) {

                        $index = $this->{$column};

                    }

                }

            }

            $sorted[] = $index;

            unset($toSort[array_search($index->getName(), $toSort)]);
            $toSort = array_values($toSort);

        }

        return $sorted;

    }

}