<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCheckIfMidDateReached()
    {
        $notification = new \App\Http\Controllers\NotificationController();
        $this->assertFalse($notification->checkIfMidDateReached(new \Carbon\Carbon('10/10/2010'),new \Carbon\Carbon('10/10/2010')));
        $this->assertFalse($notification->checkIfMidDateReached(new \Carbon\Carbon('10/01/2010'),new \Carbon\Carbon('10/10/2010')));
        $this->assertFalse($notification->checkIfMidDateReached(new \Carbon\Carbon('10/01/2010'),new \Carbon\Carbon('10/10/2009')));
        $this->assertTrue($notification->checkIfMidDateReached(\Carbon\Carbon::now()->format('d-m-Y'),\Carbon\Carbon::now()->format('d-m-Y')));
    }
 
}
