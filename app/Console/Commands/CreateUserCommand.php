<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\User\CreateUserAction;
use Illuminate\Console\Command;
use Illuminate\Validation\Rules\Password;

use function Laravel\Prompts\form;

final class CreateUserCommand extends Command
{
    protected $signature = 'user:create';

    protected $description = 'Command description';

    public function __construct(
        private readonly CreateUserAction $createUserAction,
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        $data = form()
            ->text(
                label: 'What is your first name?',
                required: true,
                validate: ['required', 'string', 'max:50'],
                name: 'first_name',
            )
            ->text(
                label: 'What is your last name?',
                required: true,
                validate: ['required', 'string', 'max:50'],
                name: 'last_name',
            )
            ->text(
                label: 'What is your email?',
                required: true,
                validate: ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                name: 'email',
            )
            ->password(
                label: 'What is your password?',
                required: true,
                validate: ['password' => Password::default()],
                hint: 'Minimum 8 characters.',
                name: 'password',
            )
            ->submit();

        $data['email_verified_at'] = now();

        $user = $this->createUserAction->execute($data);

        info(sprintf('User "%s" created successfully.', $user->email));
    }
}
