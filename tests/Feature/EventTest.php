<?php

namespace Tests\Feature;

use App\Services\CalendarService;
use Google\Service\AIPlatformNotebooks\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EventTest extends TestCase
{
    use WithFaker;

    private CalendarService $calendar;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calendar = new CalendarService;
    }
    /**
     * A basic feature test example.
     */
    public function test_create_event(): void
    {
        $payload = [
            "title" => $this->faker->sentence(3),
            "start_time" => now()->addDay(),
            "end_time" => now()->addDays(2)
        ];
        $response = $this->createDummyEvent($payload);
        $event = $response->json();
        $response->assertCreated();
        $this->assertEquals($payload["title"], $event['title']);
        $this->calendar->delete($event["id"]);
    }

    public function test_get_all_events(): void
    {

        $response = $this->get(route("events.index"));
        $event_list = $response->json();
        $event = $this->createDummyEvent()->json();
        $response->assertOk();
        $response->assertJsonStructure([
            "data" => [
                "*" => ["id", "title", "start_time", "end_time"]
            ]
        ]);
        $this->calendar->delete($event["id"]);
    }

    public function test_delete_event(): void
    {
        $event = $this->createDummyEvent()->json();
        $response = $this->delete(
            route("events.destroy", [
                "event" => $event["id"]
            ])
        );
        $response->assertNoContent();
    }

    private function createDummyEvent(array $payload = null): \Illuminate\Testing\TestResponse
    {
        if (is_null($payload))
            $payload = [
                "title" => $this->faker->sentence(3),
                "start_time" => now()->addDay(),
                "end_time" => now()->addDays(2)
            ];
        return $this->post(route("events.store"), $payload);
    }
}
