<?php

namespace Tests;

use App\Models\RentalContract;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        RentalContract::allowStateAssignment();
    }
}
