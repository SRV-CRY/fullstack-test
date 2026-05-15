<?php

namespace App\Models;

use CodeIgniter\Model;

class Comment extends Model
{
    protected $table = 'comments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['name', 'text', 'date', 'created_at'];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Валидация на сервере
    protected $validationRules = [
        'name' => 'required|valid_email',
        'text' => 'required|min_length[3]',
        'date' => 'required'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Email обязателен',
            'valid_email' => 'Введите корректный email'
        ],
        'text' => [
            'required' => 'Текст комментария обязателен',
            'min_length' => 'Минимум 3 символа'
        ],
        'date' => [
            'required' => 'Дата обязательна'
        ]
    ];
}
