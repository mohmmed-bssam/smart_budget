<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command(
    'goals:deadline-reminders'
)->dailyAt('09:00');
