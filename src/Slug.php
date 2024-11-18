<?php

namespace GcProfiler;

use Cocur\Slugify\Slugify;

class Slug
{
    private static ?Slugify $slugify = null;

    public static function slug(string $class, string $suffix): string
    {
        $name = $class;

        $name = explode('\\', $name);

        $name = array_pop($name);

        // camel case to snake case
        $name = preg_replace('/(?<!^)[A-Z]/', '_$0', $name);

        if (self::$slugify === null) {
            self::$slugify = Slugify::create();
        }

        return self::$slugify->slugify(strtolower($name) . '.' . $suffix, '::');
    }
}
