<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('sso:prune-tokens')->hourly();
Schedule::command('sessions:partition')->daily();
