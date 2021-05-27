<?php


namespace LaFabrique\CSVMaker;


class Column
{

    protected $values = [];

    protected $name;

    protected $alias;

    protected $order;

    protected $priority;

    protected $conditions;

    public function addValue($value) {

        if(is_null($this->conditions)) {

            $this->values[] = $value;

        } else {

            $calcul = function () use ($value) {

                $formated = $value;

                foreach ($this->conditions as $condition) {

                    if($value == $condition[0]) {

                        $formated = $condition[1];

                    }

                }

                return $formated;

            };

            $this->values[] = $calcul();

        }

    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param array $values
     */
    public function setValues($values)
    {
        $this->values = $values;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param mixed $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return mixed
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param mixed $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return mixed
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @param mixed $conditions
     */
    public function setConditions($conditions)
    {
        $this->conditions = $conditions;
    }

}