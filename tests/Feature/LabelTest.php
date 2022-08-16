<?php

namespace Tests\Feature;

use App\Models\Label;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LabelTest extends TestCase
{
    use RefreshDatabase;
    public function setUp():void
    {
        parent::setUp();
        $this->user = $this->authUser();
    }

    /** @test */
    public function user_can_store_label()
    {
        $label = Label::factory()->make();
        $this->postJson(route('label.store'),[
            'title' => $label->title,
            'color' => $label->color
        ])->assertCreated();

        $this->assertDatabaseHas('labels',[
            'title' => $label->title,
            'color' => $label->color
        ]);
    }

    /** @test */
    public function user_can_delete_label()
    {
        $label = $this->createLabel();
        $this->deleteJson(route('label.destroy',$label))->assertNoContent();

        $this->assertDatabaseMissing('labels',[$label->id]);
    }

    /** @test */
    public function user_can_update_label()
    {
        $label = $this->createLabel();
        $this->patchJson(route('label.update',$label),[
            'title' => $label->title,
            'color' => 'my-color'
        ])->assertOk();

        $this->assertDatabaseHas('labels',[
            'title' => $label->title,
            'color' => 'my-color'
        ]);
    }

    /** @test */
    public function fetch_all_labels_for_a_user()
    {
        $label = $this->createLabel(['user_id' => $this->user->id]);
        $response = $this->getJson(route('label.index'))->assertOk()->json();

        $this->assertEquals($response[0]['title'],$label->title);
    }
}
