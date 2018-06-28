<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 23.04.18
 * Time: 11:14
 */

namespace App\Http;


/**
 * Class AbstractDataClass
 *
 * @package App\Http
 */
abstract class AbstractDataClass
{
    protected $_data = [];
    
    
    /**
     * @param $name
     *
     * @return mixed|null
     */
    public function __get($name)
    {
        if (!isset($this->_data[$name])) {
            switch ($name) {
                default:
                    return null;
            }
        }
        
        return $this->_data[$name];
    }
    
    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        switch ($name) {
            default:
                $this->_data[$name] = $value;
        }
    }
    
    /**
     * @param $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->_data[$name]);
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }
    
    /**
     * @return array
     */
    public function toArray()
    {
        return $this->_data;
    }
    
    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode(
            $this->toArray(),
            JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE
        );
    }
    
    /**
     * @return string
     */
    public function toBase64()
    {
        return base64_encode($this->toJson());
    }
    
}