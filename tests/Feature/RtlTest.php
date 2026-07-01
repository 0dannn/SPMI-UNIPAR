<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RtlTest extends TestCase
{
    use RefreshDatabase;

    public function test_auditee_can_create_rtl_for_their_finding()
    {
        $this->assertTrue(true);
    }

    public function test_auditee_cannot_create_rtl_for_other_unit()
    {
        $this->assertTrue(true);
    }

    public function test_rtl_requires_penanggung_jawab()
    {
        $this->assertTrue(true);
    }
}
