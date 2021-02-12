<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc4f132922cfc3320628776d62cac7ae5
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'Wrpl\\Inc\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Wrpl\\Inc\\' => 
        array (
            0 => __DIR__ . '/../..' . '/inc',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc4f132922cfc3320628776d62cac7ae5::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc4f132922cfc3320628776d62cac7ae5::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}