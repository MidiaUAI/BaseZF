<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Base\Plugin;

class EntityManager
{

    public static $entityManager;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        self::$entityManager = $em;
    }

    public static function setEm(\Doctrine\ORM\EntityManager $em)
    {
        self::$entityManager = $em;
    }

    public static function getEm()
    {
        return self::$entityManager;
    }
}
