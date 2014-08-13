<?php
class Autoloader
{
    private $folder;

    /**
     * Constructor
     *
     * @param string $folder
     */
    public function __construct( $folder = null )
    {
        if ( ! $folder )
        {
            $folder = dirname(__FILE__) . '/..';
        }

        $this->folder = $folder;
    }

    /**
     * Handel autoloading of classes
     *
     * @param $class
     * @return bool|mixed
     */
    public function autoload( $class )
    {
        $filePath = $this->folder . '/' . str_replace('\\', '/', $class) . '.php';

        if (file_exists($filePath))
        {
            return ( (require_once $filePath) === false ? true : false );
        }

        return false;
    }

    /**
     * Register SPL Autoload
     *
     * @param null $folder
     * @return bool
     */
    public static function register( $folder = null )
    {
        ini_set('unserialize_callback_func', 'spl_autoload_call');

        return spl_autoload_register(
            array(
                new self($folder),
                'autoload'
            )
        );
    }
}