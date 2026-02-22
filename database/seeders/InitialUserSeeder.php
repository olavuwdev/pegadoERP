<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class InitialUserSeeder extends Seeder
{
    public function run(): void
    {
        if (User::query()->exists()) {
            return;
        }

        $name = (string) env('INITIAL_USER_NAME', '');
        $email = (string) env('INITIAL_USER_EMAIL', '');
        $password = (string) env('INITIAL_USER_PASSWORD', '');

        if ($name === '' || $email === '' || $password === '') {
            return;
        }

        $attributes = [
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
        ];

        $cpfCnpj = (string) env('INITIAL_USER_CPF_CNPJ', '');
        if ($cpfCnpj !== '' && Schema::hasColumn('users', 'cpf_cnpj')) {
            $attributes['cpf_cnpj'] = $cpfCnpj;
        }

        $user = new User();
        $user->forceFill($attributes);
        $user->save();
    }
}

