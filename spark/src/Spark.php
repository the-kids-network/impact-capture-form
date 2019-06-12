<?php

namespace Laravel\Spark;

class Spark
{
    use Configuration\CallsInteractions,
        Configuration\ManagesAppOptions,
        Configuration\ManagesModelOptions,
        Configuration\ManagesSupportOptions,
        Configuration\ProvidesScriptVariables;

    /**
     * The Spark version.
     */
    public static $version = '5.0.1';
}
