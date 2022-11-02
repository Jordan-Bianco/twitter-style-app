<?php

namespace App\models;

use App\core\Application;
use App\core\database\QueryBuilder;

abstract class Model
{
    protected QueryBuilder $builder;

    public function __construct()
    {
        $this->builder = Application::$app->builder;
    }

    /**
     * @return string 
     */
    abstract public function table(): string;

    /**
     * @param array $data
     * @return bool
     */
    public function create(array $data): bool
    {
        $this->checkFillableFields($data);

        return $this->builder->insert($this->table(), $data);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function update(array $data): bool
    {
        $this->checkFillableFields($data);

        return $this->builder->insert($this->table(), $data);
    }

    /**
     * Check that the fields sent through the form are the same as those present in the fillables array of the model (if present)
     * 
     * @param array $data
     * @return void
     * @throws \Exception
     */
    protected function checkFillableFields(array $data): void
    {
        if (!property_exists($this, 'fillables'))
            return;

        $differentKey = array_diff(array_keys($data), $this->fillables);

        if ($differentKey) {
            foreach ($differentKey as $key) {
                throw new \Exception("The $key key is not present in the " . get_class($this) . " fillables array");
            }
        }
    }
}
