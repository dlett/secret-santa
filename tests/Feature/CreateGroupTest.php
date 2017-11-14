<?php

namespace Tests\Feature;

use App\Group;
use App\Member;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateGroupTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    function testBasicTest()
    {
        $data = [
            'name' => 'Group Name',
            'members' => [
                ['name' => 'Daniel'],
                ['name' => 'Stephen'],
                ['name' => 'Mikey'],
                ['name' => 'Chris', 'unconnectables' => ['Kaitlyn']],
                ['name' => 'Kaitlyn', 'unconnectables' => ['Chris']],
                ['name' => 'Brittney', 'unconnectables' => ['Nolan']],
                ['name' => 'Nolan', 'unconnectables' => ['Brittney']],
            ]
        ];

        $response = $this->post('/group', $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('groups', ['name' => $data['name']]);

//        dd(Member::with(['connection' => function ($builder) {$builder->select(['id', 'name']);}])->get()->toArray());
        dump(Group::query()->latest()->first()->toArray());
    }
}
