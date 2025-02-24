<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\Users\CreateUserAction;
use Illuminate\Console\Command;
use Illuminate\Validation\Rules\Password;
use InvalidArgumentException;

use function Laravel\Prompts\form;

final class CreateUserCommand extends Command
{
    protected $signature = 'users:create {--first_name=} {--last_name=} {--email=} {--password=}';

    protected $description = 'Command description';

    public function __construct(
        private readonly CreateUserAction $createUserAction,
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        $isInteractive = stream_isatty(STDIN);

        if ($isInteractive) {
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
        } else {
            $data = [
                'first_name' => $this->option('first_name') ?? throw new InvalidArgumentException('First name is required.'),
                'last_name' => $this->option('last_name') ?? throw new InvalidArgumentException('Last name is required.'),
                'email' => $this->option('email') ?? throw new InvalidArgumentException('Email is required.'),
                'password' => $this->option('password') ?? throw new InvalidArgumentException('Password is required.'),
            ];
        }

        $user = $this->createUserAction->execute([
            ...$data,
            'email_verified_at' => now(),
        ]);

        $this->info(sprintf('User "%s" created successfully.', $user->email));
    }
}
