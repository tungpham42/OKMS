<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitf10cc5bc30d5c70340214d9a1fe03faf
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInitf10cc5bc30d5c70340214d9a1fe03faf', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitf10cc5bc30d5c70340214d9a1fe03faf', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitf10cc5bc30d5c70340214d9a1fe03faf::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
