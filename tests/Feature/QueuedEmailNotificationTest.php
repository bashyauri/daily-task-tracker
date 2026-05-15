<?php

use App\Models\User;
use App\Notifications\QueuedResetPasswordNotification;
use App\Notifications\QueuedVerifyEmailNotification;
use Illuminate\Notifications\SendQueuedNotifications;
use Illuminate\Support\Facades\Queue;

test('password reset emails are queued', function () {
    Queue::fake();

    $user = new User([
        'email' => 'taylor@example.com',
    ]);

    $user->sendPasswordResetNotification('reset-token');

    Queue::assertPushed(SendQueuedNotifications::class, function (SendQueuedNotifications $job) {
        return $job->notification instanceof QueuedResetPasswordNotification
            && $job->channels === ['mail']
            && $job->tries === 3
            && $job->backoff() === [1, 5, 10];
    });
});

test('email verification notifications are queued', function () {
    Queue::fake();

    $user = new User([
        'id' => 1,
        'email' => 'taylor@example.com',
    ]);

    $user->sendEmailVerificationNotification();

    Queue::assertPushed(SendQueuedNotifications::class, function (SendQueuedNotifications $job) {
        return $job->notification instanceof QueuedVerifyEmailNotification
            && $job->channels === ['mail']
            && $job->tries === 3
            && $job->backoff() === [1, 5, 10];
    });
});
