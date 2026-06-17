<?php

namespace App\Console\Commands;

use App\Support\DevelopmentSessionManager;
use Illuminate\Foundation\Console\ServeCommand as BaseServeCommand;

class ServeCommand extends BaseServeCommand
{
    public function handle()
    {
        if (app()->environment('local')) {
            DevelopmentSessionManager::reset();
            $this->components->info('Development sessions cleared. Users must log in again.');
        }

        return parent::handle();
    }
}
