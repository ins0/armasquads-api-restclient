<?php
namespace ArmAsquads\Api\Entity;

/**
 * Interface EntityInterface
 *
 * @author      Marco Rieger
 * @copyright   Copyright (c) 2013 Marco Rieger (http://racecore.de)
 * @package     ArmAsquads\Api\Entity
 */
interface EntityInterface
{
    /**
     * Exchange API response to Squad Object
     *
     * @param $array
     * @return $this
     */
    public function exchangeArray($array);

    /**
     * Get ArrayCopy of Object
     * @return array
     */
    public function getArrayCopy();
}