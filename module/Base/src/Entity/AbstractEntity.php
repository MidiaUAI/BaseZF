<?php

namespace App\Entity;

use Laminas\Hydrator;

/**
 * Description of abstractEntity
 *
 * @author Thalles
 */
abstract class AbstractEntity
{
    public function __construct($options = null)
    {
        if ($options) {
            (new Hydrator\ClassMethodsHydrator())->hydrate($options, $this);
        }
    }

    abstract public function getId();

    public function __toString()
    {
        return (string) $this->getId();
    }

    public function __get($name)
    {
        $get = 'get' . $name;
        return $this->$get();
    }

    public function __set($name, $value)
    {
        $set = 'set' . $name;
        $this->$set($value);
    }

    /**
     * Get var names
     *
     * @return Array
     */
    public function getVarNames()
    {
        return get_class_vars(static::class);
    }

    /**
     * Get NomeClasse
     *
     * @return string
     */
    public function getClassName()
    {
        $class = explode('\\', static::class);
        return ucfirst(end($class));
    }

    public function toArray()
    {

        $arrayTo = [];

        foreach ($this->getVarNames() as $chav => $valor):
            $metodo = 'get' . ucfirst($chav);
            if (is_object($this->$metodo())) {
                if (method_exists($this->$metodo(), 'format')) {
                    if ($this->$metodo()->format('Y') == 1970)
                        $arrayTo[$chav] = $this->$metodo()->format('H:i');
                    else
                        $arrayTo[$chav] = $this->$metodo()->format('Y-m-d');

                } elseif (method_exists($this->$metodo(), 'getId')) {
                    $arrayTo[$chav] = $this->$metodo()->getId();
                } elseif (method_exists($this->$metodo(), 'getPessoa')) {
                    $arrayTo[$chav] = $this->$metodo()->getPessoa()->getId();
                }
            } else {
                $arrayTo[$chav] = $this->$metodo();
            }
        endforeach;
        return $arrayTo;
    }

    public function getDateObject($date)
    {
        if (count(explode("-", $date)) > 1) {
            return \DateTime::createFromFormat('Y-m-d', $date);
        } elseif (count(explode("/", $date)) > 1) {
            return \DateTime::createFromFormat('d/m/Y', $date);
        }
        return null;
    }
}
