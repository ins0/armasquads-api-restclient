<?php
namespace ArmAsquads\Api\Entity;

/**
 * Class AbstractEntity
 *
 * @author      Marco Rieger
 * @copyright   Copyright (c) 2013 Marco Rieger (http://racecore.de)
 * @package     ArmAsquads\Api\Entity
 */
abstract class AbstractEntity implements EntityInterface
{
    /**
     * Exchange API response to Squad Object
     *
     * @param $array
     * @return $this
     */
    public function exchangeArray($array)
    {
        $self = $this;
        $vars = get_class_vars(get_class($this));
        array_map(function($v, $k) use ($vars, $self) {
            if( method_exists($self, 'set' . ucwords(strtolower($k)) ) )
            {
                call_user_func(array($self, 'set' . ucwords(strtolower($k))), $v);
            }
        }, $array, array_keys($array));

        return $this;
    }

    /**
     * Get ArrayCopy of Object
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}