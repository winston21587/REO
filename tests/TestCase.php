<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (DB::connection()->getDriverName() === 'sqlite') {
            $pdo = DB::connection()->getPdo();
            if (method_exists($pdo, 'sqliteCreateFunction')) {
                $pdo->sqliteCreateFunction('YEAR', fn($date) => $date ? (int)date('Y', strtotime($date)) : null, 1);
                $pdo->sqliteCreateFunction('MONTH', fn($date) => $date ? (int)date('m', strtotime($date)) : null, 1);
                $pdo->sqliteCreateFunction('WEEK', fn($date) => $date ? (int)date('W', strtotime($date)) : null, 1);
                $pdo->sqliteCreateFunction('DATE', fn($date) => $date ? date('Y-m-d', strtotime($date)) : null, 1);
            }
        }
    }
}
