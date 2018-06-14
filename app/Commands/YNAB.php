<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

abstract class YNAB extends Command
{
    const PREFIX = 'ynab:';

    public function __construct()
    {
        //set prefix
        $this->signature = self::PREFIX . $this->signature;

        parent::__construct();
    }
}