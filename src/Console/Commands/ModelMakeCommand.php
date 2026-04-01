<?php

namespace Gingerminds\LaravelCore\Console\Commands;

use Illuminate\Foundation\Console\ModelMakeCommand as BaseModelMakeCommand;

class ModelMakeCommand extends BaseModelMakeCommand
{
    /**
     * Retourne le chemin du stub custom.
     */
    protected function getStub()
    {
        // Chemin vers ton stub dans le vendor
        return base_path('stubs/model.stub');
    }
}