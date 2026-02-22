<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('user:create {name : Nome do usuário} {email : E-mail do usuário} {--password= : Senha (se omitida, será solicitada)} {--cpf_cnpj= : CPF/CNPJ (opcional, se existir a coluna)}', function () {
    $name = (string) $this->argument('name');
    $email = (string) $this->argument('email');
    $password = $this->option('password') ?: $this->secret('Senha');

    if (!$password) {
        $this->error('Senha obrigatória.');
        return self::FAILURE;
    }

    if (User::query()->where('email', $email)->exists()) {
        $this->error("Já existe um usuário com o e-mail {$email}.");
        return self::FAILURE;
    }

    $attributes = [
        'name' => $name,
        'email' => $email,
        'password' => Hash::make($password),
        'email_verified_at' => now(),
    ];

    $cpfCnpj = $this->option('cpf_cnpj');
    if ($cpfCnpj && Schema::hasColumn('users', 'cpf_cnpj')) {
        $attributes['cpf_cnpj'] = $cpfCnpj;
    }

    $user = new User();
    $user->forceFill($attributes);
    $user->save();

    $this->info("Usuário criado: #{$user->id} ({$user->email})");
    return self::SUCCESS;
})->purpose('Cria usuário manualmente (primeiro acesso)');
