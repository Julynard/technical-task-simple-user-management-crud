<?php

namespace App\Models;

/**
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $company_name
 * @property string $email
 * @property string $contact_number
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $restored_at
 */
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'company_name',
        'email',
        'contact_number',
        'description',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'deleted_at' => 'datetime',
        'restored_at' => 'datetime',
    ];
}
