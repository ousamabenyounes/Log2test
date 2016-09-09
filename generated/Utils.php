<?php


trait Utils
{
    /**
     * Current Class Name
     *
     * @var string
     */
    protected $className;

    /**
     * Create a Directory
     *
     * @param $path
     * @return bool
     */
    public function createDir($path)
    {
        if (!file_exists($path)) {
            return mkdir($path);
        }
    }

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
