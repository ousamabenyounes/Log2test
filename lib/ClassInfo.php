<?php

namespace Log2Test;

trait ClassInfo
{
    /**
     * Current Class Name
     *
     * @var string
     */
    protected $className;


    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param string $className
     */
    public function setClassName($className)
    {
        $this->className = $className;
    }

}
