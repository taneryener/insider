<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTruncation;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use DatabaseTruncation;

    public function test_returns_a_successful_response()
    {
        $response = $this->get('/up');

        $response->assertStatus(200);
    }
}
