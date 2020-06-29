<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WelcomeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function the_homepage_can_be_visited()
    {
        $response = $this->get(route('welcome'));

        $response->assertSee('Bárdi mozi');

        $response->assertOk();
    }
}
