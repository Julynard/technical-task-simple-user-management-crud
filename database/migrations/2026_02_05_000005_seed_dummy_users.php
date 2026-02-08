<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = Carbon::now();

        $rows = [
            [
                'first_name' => 'Ada',
                'last_name' => 'Lovelace',
                'company_name' => 'Analytical Engines Inc.',
                'email' => 'ada@example.com',
                'contact_number' => '+1-555-0101',
                'description' => 'First programmer.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'first_name' => 'Grace',
                'last_name' => 'Hopper',
                'company_name' => 'COBOL Systems',
                'email' => 'grace@example.com',
                'contact_number' => '+1-555-0102',
                'description' => 'Pioneer in compiler design.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'first_name' => 'Alan',
                'last_name' => 'Turing',
                'company_name' => 'Bletchley Park',
                'email' => 'alan@example.com',
                'contact_number' => '+1-555-0103',
                'description' => 'Foundations of computer science.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'first_name' => 'Katherine',
                'last_name' => 'Johnson',
                'company_name' => 'NASA',
                'email' => 'katherine@example.com',
                'contact_number' => '+1-555-0104',
                'description' => 'Orbital mechanics calculations.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'first_name' => 'Tim',
                'last_name' => 'Berners-Lee',
                'company_name' => 'World Wide Web Foundation',
                'email' => 'tim@example.com',
                'contact_number' => '+1-555-0105',
                'description' => 'Invented the Web.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('users')->upsert(
            $rows,
            ['email'],
            ['first_name', 'last_name', 'company_name', 'contact_number', 'description', 'updated_at']
        );
    }

    public function down(): void
    {
        DB::table('users')
            ->whereIn('email', [
                'ada@example.com',
                'grace@example.com',
                'alan@example.com',
                'katherine@example.com',
                'tim@example.com',
            ])
            ->delete();
    }
};
