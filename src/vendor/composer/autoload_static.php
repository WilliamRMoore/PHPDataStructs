<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit224d1d4bf2b536fe2c13054a5cd7b084
{
    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'DSL\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'DSL\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit224d1d4bf2b536fe2c13054a5cd7b084::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit224d1d4bf2b536fe2c13054a5cd7b084::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit224d1d4bf2b536fe2c13054a5cd7b084::$classMap;

        }, null, ClassLoader::class);
    }
}