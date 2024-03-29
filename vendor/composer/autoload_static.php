<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitff337fc4959c6f2b657839c10ef9518b
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PMS\\' => 4,
        ),
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PMS\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitff337fc4959c6f2b657839c10ef9518b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitff337fc4959c6f2b657839c10ef9518b::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitff337fc4959c6f2b657839c10ef9518b::$classMap;

        }, null, ClassLoader::class);
    }
}
