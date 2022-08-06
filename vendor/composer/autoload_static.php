<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite935a8f52aef325c7594a86365d6e235
{
    public static $prefixLengthsPsr4 = array (
        'I' => 
        array (
            'Interkassa\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Interkassa\\' => 
        array (
            0 => __DIR__ . '/..' . '/interkassa/php-sdk/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite935a8f52aef325c7594a86365d6e235::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite935a8f52aef325c7594a86365d6e235::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInite935a8f52aef325c7594a86365d6e235::$classMap;

        }, null, ClassLoader::class);
    }
}
