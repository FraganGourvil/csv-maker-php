<?php


namespace LaFabrique\CSVMaker;


use LaFabrique\CSVMaker\Exceptions\ProtectedFieldNameException;

class Line
{

    protected $callback;

    protected $fromRow;

    private $properties = [];

    /**
     * Line constructor.
     * @param $fromRow
     */
    public function __construct($fromRow)
    {
        $this->fromRow = $fromRow;

        $this->callback = null;

    }

    public function __get($key) {

        return (isset($this->properties[$key])) ? $this->properties[$key] : null;

    }

    public function getProperties() {

        return $this->properties;

    }

    public function existProperty($property) {

        return isset($this->properties[$property]);

    }

    public function write() {

        $reflection = new \ReflectionClass($this);

        foreach ($this->fromRow as $column => $value) {

            if(isset($this->{$column})) {

                throw new ProtectedFieldNameException("Le champ {$column} est protégé et ne peux être utilisé comme élément du CSV.");

            }

            if(is_null($value)) {

                $value = "NULL";

            }

            if(isset($this->callback)) {

                $this->properties[$column] = call_user_func_array($this->callback, [$value]);

            } else {

                $this->properties[$column] = $value;

            }

        }

        return $this;

    }

}