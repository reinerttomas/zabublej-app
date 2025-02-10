<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\User\CreateUserAction;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Validation\Rule;
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
                label: 'What is your name?',
                required: true,
                validate: ['required', 'string', 'max:255'],
                name: 'name',
            )
            ->text(
                label: 'What is your email?',
                required: true,
                validate: ['required', 'string', 'email', 'max:255', Rule::unique(User::class, 'email')],
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

        $user = $this->createUserAction->execute($data);

        info(sprintf('User "%s" created successfully.', $user->email));
    }
}
