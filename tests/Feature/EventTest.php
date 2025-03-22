<?php

declare(strict_types=1);

use App\Enums\EventStatus;
use App\Models\Event;
use App\Models\User;
use App\Notifications\EventAssignmentNotification;
use App\Notifications\EventCancelledNotification;
use Livewire\Volt\Volt;

use function Pest\Faker\fake;
use function Pest\Laravel\actingAs;

it('redirects to login page as guest', function (): void {
    $this->get('/events')->assertRedirect('/login');
});

it('allows admin and staff to show events list', function (User $user): void {
    actingAs($user)
        ->get('/events')
        ->assertStatus(200);
})->with([
    fn () => User::factory()->superAdmin()->create(),
    fn () => User::factory()->admin()->create(),
    fn () => User::factory()->staff()->create(),
]);

it('allows admin to show create event button', function (User $user): void {
    actingAs($user)
        ->get('/events')
        ->assertStatus(200)
        ->assertSeeLivewire('events.create-event-form');
})->with([
    fn () => User::factory()->superAdmin()->create(),
    fn () => User::factory()->admin()->create(),
]);

it('forbids staff to show create event button', function (User $user): void {
    actingAs($user)
        ->get('/events')
        ->assertStatus(200)
        ->assertDontSeeLivewire('events.create-event-form');
})->with([
    fn () => User::factory()->staff()->create(),
]);

it('allows admin and staff to show event details', function (User $user, Event $event): void {
    actingAs($user)
        ->get("/events/{$event->id}")
        ->assertSee('Odměna pro pracovníka:')
        ->assertSee($event->reward);
})->with([
    fn (): array => [
        User::factory()->superAdmin()->create(),
        Event::factory()->create(),
    ],
    fn (): array => [
        User::factory()->admin()->create(),
        Event::factory()->create(),
    ],
    function (): array {
        $user = User::factory()->staff()->create();
        $event = Event::factory()->hasAttached($user)->create();

        return [
            $user,
            $event,
        ];
    },
]);

it('forbids staff to show edit button', function (User $user, Event $event): void {
    actingAs($user)
        ->get("/events/{$event->id}")
        ->assertDontSee('Upravit')
        ->assertDontSee('Cena události:')
        ->assertSee('Odměna pro pracovníka:')
        ->assertSee($event->reward);
})->with([
    function (): array {
        $user = User::factory()->staff()->create();
        $event = Event::factory()->hasAttached($user)->create();

        return [
            $user,
            $event,
        ];
    },
]);

it('forbids staff to show event when is not assigned', function (User $user, Event $event): void {
    actingAs($user)
        ->get("/events/{$event->id}")
        ->assertStatus(403);
})->with([
    fn (): array => [
        User::factory()->staff()->create(),
        Event::factory()->create(),
    ],
]);

it('forbids staff to show event price', function (User $user, Event $event): void {
    actingAs($user)
        ->get("/events/{$event->id}")
        ->assertDontSee('Cena události:');
})->with([
    fn (): array => [
        User::factory()->staff()->create(),
        Event::factory()->create(),
    ],
]);

it('allows admin to show event edit page', function (User $user, Event $event): void {
    actingAs($user)
        ->get("/events/{$event->id}/edit")
        ->assertStatus(200)
        ->assertSeeLivewire('events.update-event-form')
        ->assertSeeLivewire('events.update-event-user-form');

})->with([
    fn (): array => [
        User::factory()->superAdmin()->create(),
        Event::factory()->create(),
    ],
    fn (): array => [
        User::factory()->admin()->create(),
        Event::factory()->create(),
    ],
]);

it('forbids staff to show event edit page', function (User $user, Event $event): void {
    actingAs($user)
        ->get("/events/{$event->id}/edit")
        ->assertStatus(403);
})->with([
    fn (): array => [
        User::factory()->staff()->create(),
        Event::factory()->create(),
    ],
]);

it('allows admin to update event', function (User $user, Event $event, array $data): void {
    actingAs($user);

    Volt::test('events.update-event-form', ['event' => $event])
        ->set('name', $data['name'])
        ->set('description', $data['description'])
        ->set('startDate', $data['startDate'])
        ->set('startTime', $data['startTime'])
        ->set('location', $data['location'])
        ->set('contactPerson', $data['contactPerson'])
        ->set('contactEmail', $data['contactEmail'])
        ->set('contactPhone', $data['contactPhone'])
        ->set('isMultiPerson', $data['isMultiPerson'])
        ->set('childrenCount', $data['childrenCount'])
        ->set('workersCount', $data['workersCount'])
        ->set('price', $data['price'])
        ->set('reward', $data['reward'])
        ->set('note', $data['note'])
        ->call('update')
        ->assertStatus(200)
        ->assertHasNoErrors();

    $event->refresh();

    expect($event)
        ->name->toEqual($data['name'])
        ->description->toEqual($data['description'])
        ->location->toEqual($data['location'])
        ->contact_person->toEqual($data['contactPerson'])
        ->contact_email->toEqual($data['contactEmail'])
        ->contact_phone->toEqual($data['contactPhone'])
        ->is_multi_person->toEqual($data['isMultiPerson'])
        ->children_count->toEqual($data['childrenCount'])
        ->workers_count->toEqual($data['workersCount'])
        ->price->toEqual($data['price'])
        ->reward->toEqual($data['reward']);
})->with([
    function (): array {
        $startAt = fake()->dateTimeBetween('+1 day', '+1 month');

        return [
            User::factory()->superAdmin()->create(),
            Event::factory()->create(),
            [
                'name' => fake()->text(),
                'description' => fake()->text(),
                'startDate' => $startAt->format('Y-m-d'),
                'startTime' => $startAt->format('H:i'),
                'location' => fake()->optional()->address(),
                'contactPerson' => fake()->optional()->name(),
                'contactEmail' => fake()->optional()->email(),
                'contactPhone' => fake()->optional()->e164PhoneNumber(),
                'isMultiPerson' => fake()->optional()->boolean(),
                'childrenCount' => fake()->optional()->numberBetween(10, 100),
                'workersCount' => fake()->optional()->numberBetween(1, 3),
                'price' => fake()->optional()->numberBetween(10000, 50000),
                'reward' => fake()->optional()->numberBetween(1000, 5000),
                'note' => fake()->optional()->text(),
            ],
        ];
    },
    function (): array {
        $startAt = fake()->dateTimeBetween('+1 day', '+1 month');

        return [
            User::factory()->admin()->create(),
            Event::factory()->create(),
            [
                'name' => fake()->text(),
                'description' => fake()->text(),
                'startDate' => $startAt->format('Y-m-d'),
                'startTime' => $startAt->format('H:i'),
                'location' => fake()->optional()->address(),
                'contactPerson' => fake()->optional()->name(),
                'contactEmail' => fake()->optional()->email(),
                'contactPhone' => fake()->optional()->e164PhoneNumber(),
                'isMultiPerson' => fake()->optional()->boolean(),
                'childrenCount' => fake()->optional()->numberBetween(10, 100),
                'workersCount' => fake()->optional()->numberBetween(1, 3),
                'price' => fake()->optional()->numberBetween(10000, 50000),
                'reward' => fake()->optional()->numberBetween(1000, 5000),
                'note' => fake()->optional()->text(),
            ],
        ];
    },
]);

it('allows admin to add worker to event', function (User $user, Event $event, User $worker): void {
    actingAs($user);

    Volt::test('events.update-event-user-form', ['event' => $event])
        ->set('userId', $worker->id)
        ->call('addUser')
        ->assertStatus(200)
        ->assertHasNoErrors();

    $event->refresh();

    expect($event->users)->toHaveCount(1)
        ->first()
        ->id->toBe($worker->id);
})->with([
    fn (): array => [
        User::factory()->superAdmin()->create(),
        Event::factory()->create(),
        User::factory()->create(),
    ],
    fn (): array => [
        User::factory()->admin()->create(),
        Event::factory()->create(),
        User::factory()->create(),
    ],
]);

it('allows admin to remove worker from event', function (User $user, Event $event, User $worker): void {
    actingAs($user);

    expect($event->users)->toHaveCount(1);

    Volt::test('events.update-event-user-form', ['event' => $event])
        ->call('removeUser', $worker->id)
        ->assertStatus(200)
        ->assertHasNoErrors();

    $event->refresh();

    expect($event->users)->toHaveCount(0);
})->with([
    function (): array {
        $worker = User::factory()->create();

        return [
            User::factory()->superAdmin()->create(),
            Event::factory()->hasAttached($worker)->create(),
            $worker,
        ];
    },
    function (): array {
        $worker = User::factory()->create();

        return [
            User::factory()->admin()->create(),
            Event::factory()->hasAttached($worker)->create(),
            $worker,
        ];
    },
]);

it('notify workers when event is published', function (User $user, Event $event, User $worker): void {
    Notification::fake();

    actingAs($user);

    expect($event->status)->toBe(EventStatus::Draft);

    Volt::test('events.edit', ['event' => $event])
        ->call('changeStatus', EventStatus::Published)
        ->assertStatus(200)
        ->assertHasNoErrors();

    $event->refresh();

    expect($event->status)->toBe(EventStatus::Published);

    Notification::assertSentTo($worker, EventAssignmentNotification::class);
})->with([
    function (): array {
        $worker = User::factory()->create();

        return [
            User::factory()->superAdmin()->create(),
            Event::factory()->draft()->hasAttached($worker)->create(),
            $worker,
        ];
    },
    function (): array {
        $worker = User::factory()->create();

        return [
            User::factory()->admin()->create(),
            Event::factory()->draft()->hasAttached($worker)->create(),
            $worker,
        ];
    },
]);

it('notify worker when assignment to published event', function (User $user, Event $event, User $worker): void {
    Notification::fake();

    actingAs($user);

    expect($event->status)->toBe(EventStatus::Draft);

    Volt::test('events.edit', ['event' => $event])
        ->call('changeStatus', EventStatus::Published)
        ->assertStatus(200)
        ->assertHasNoErrors();

    $event->refresh();

    expect($event->status)->toBe(EventStatus::Published);

    Notification::assertSentTo($worker, EventAssignmentNotification::class);
})->with([
    function (): array {
        $worker = User::factory()->create();

        return [
            User::factory()->superAdmin()->create(),
            Event::factory()->draft()->hasAttached($worker)->create(),
            $worker,
        ];
    },
    function (): array {
        $worker = User::factory()->create();

        return [
            User::factory()->admin()->create(),
            Event::factory()->draft()->hasAttached($worker)->create(),
            $worker,
        ];
    },
]);

it('notify workers when event is cancelled', function (User $user, Event $event, User $worker): void {
    Notification::fake();

    actingAs($user);

    expect($event->status)->toBe(EventStatus::Published);

    Volt::test('events.edit', ['event' => $event])
        ->call('changeStatus', EventStatus::Cancelled)
        ->assertStatus(200)
        ->assertHasNoErrors();

    $event->refresh();

    expect($event->status)->toBe(EventStatus::Cancelled);

    Notification::assertSentTo($worker, EventCancelledNotification::class);
})->with([
    function (): array {
        $worker = User::factory()->create();

        return [
            User::factory()->superAdmin()->create(),
            Event::factory()->published()->hasAttached($worker)->create(),
            $worker,
        ];
    },
    function (): array {
        $worker = User::factory()->create();

        return [
            User::factory()->admin()->create(),
            Event::factory()->published()->hasAttached($worker)->create(),
            $worker,
        ];
    },
]);
