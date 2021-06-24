<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbf2e2d942ba6d7cb87e842cae56f4f15
{
    public static $prefixLengthsPsr4 = array (
        'O' => 
        array (
            'Opis\\JsonSchema\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Opis\\JsonSchema\\' => 
        array (
            0 => __DIR__ . '/..' . '/opis/json-schema/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbf2e2d942ba6d7cb87e842cae56f4f15::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbf2e2d942ba6d7cb87e842cae56f4f15::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitbf2e2d942ba6d7cb87e842cae56f4f15::$classMap;

        }, null, ClassLoader::class);
    }
}
