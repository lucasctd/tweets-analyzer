<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 20/05/2018
 * Time: 02:47
 */

namespace App\Traits;


trait ModelHelper
{
    /**
     * @param string $property
     * @param $data
     * @return mixed
     */
    public static function getValue(string $property, $data)
    {
        return property_exists($data, $property) ? $data->$property : null;
    }
}