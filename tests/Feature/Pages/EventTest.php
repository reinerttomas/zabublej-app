<?php

declare(strict_types=1);

use App\Enums\EventStatus;
use App\Models\Event;
use App\Models\User;
use Carbon\CarbonImmutable;
use Livewire\Volt\Volt;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('redirects to login page as guest', function (): void {
    // Act & Assert
    expect(get('/events'))
        ->assertRedirect('/login');
});

it('can show events page', function (): void {
    // Arrange
    actingAs(User::factory()->create());

    // Act & Assert
    get('/events')
        ->assertOk()
        ->assertSeeVolt('events.index')
        ->assertSeeVolt('events.create-event-form');
});

it('can create event', function (): void {
    // Arrange
    actingAs(User::factory()->create());

    // Act & Assert
    Volt::test('events.create-event-form')
        ->set('name', 'Test Event')
        ->set('startDate', now()->format('Y-m-d'))
        ->set('startTime', now()->addHour()->format('H:i'))
        ->call('create')
        ->assertHasNoErrors()
        ->assertRedirect('/events/' . Event::latest()->first()->id);
});

it('can show edit event page', function (): void {
    // Arrange
    actingAs(User::factory()->create());

    $event = Event::factory()->create();

    // Act & Assert
    get('/events/' . $event->id)
        ->assertOk()
        ->assertSeeVolt('events.update-event-form')
        ->assertSeeVolt('events.update-status-form')
        ->assertSeeVolt('events.update-event-user-form');
});

it('can update event information', function (array $data): void {
    // Arrange
    actingAs(User::factory()->create());

    $event = Event::factory()->create();

    // Act & Assert
    Volt::test('events.update-event-form', ['event' => $event])
        ->set('name', $data['name'])
        ->set('description', $data['description'])
        ->set('startDate', $data['startDate'])
        ->set('startTime', $data['startTime'])
        ->set('estimatedHours', $data['estimatedHours'])
        ->set('location', $data['location'])
        ->call('update')
        ->assertHasNoErrors()
        ->assertNoRedirect();

    $event->refresh();

    $startAt = CarbonImmutable::parse($data['startDate'] . ' ' . $data['startTime']);

    expect($event)
        ->name->toEqual($data['name'])
        ->description->toEqual($data['description'])
        ->start_at->toEqual($startAt)
        ->estimated_hours->toEqual($data['estimatedHours'])
        ->location->toEqual($data['location']);

})->with([
    fn (): array => [
        'name' => fake()->sentence,
        'description' => fake()->realText,
        'startDate' => CarbonImmutable::tomorrow()->format('Y-m-d'),
        'startTime' => CarbonImmutable::tomorrow()->addHour()->format('H:i'),
        'estimatedHours' => fake()->randomDigitNotZero(),
        'location' => fake()->address,
    ],
]);

it('can publish event', function (): void {
    // Arrange
    actingAs(User::factory()->create());

    $event = Event::factory()->draft()->create();

    // Act & Assert
    Volt::test('events.update-status-form', ['event' => $event])
        ->set('status', EventStatus::Published)
        ->call('update')
        ->assertHasNoErrors()
        ->assertNoRedirect();

    $event->refresh();

    expect($event->status)->toEqual(EventStatus::Published);
});

it('can assign user to event', function (): void {
    // Arrange
    // Arrange
    actingAs(User::factory()->create());

    $event = Event::factory()->create();
    $user = User::factory()->create();

    // Act & Assert
    Volt::test('events.update-event-user-form', ['event' => $event])
        ->set('assignUserId', $user->id)
        ->call('assign')
        ->assertHasNoErrors()
        ->assertNoRedirect();

    $event->refresh();

    expect($event->users)
        ->toHaveCount(1)
        ->and($event->users->first()->id)
        ->toEqual($user->id);
});

it('can unassign user from event', function (): void {
    // Arrange
    actingAs(User::factory()->create());

    $user = User::factory()->create();
    $event = Event::factory()->hasAttached($user)->create();

    // Act & Assert
    Volt::test('events.update-event-user-form', ['event' => $event])
        ->call('unassign', $user->id)
        ->assertHasNoErrors()
        ->assertNoRedirect();

    $event->refresh();

    expect($event->users)->toHaveCount(0);
});
