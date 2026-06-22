<?php

return [
    'custom' => [
        'email' => [
            'unique' => 'Этот email уже зарегистрирован в системе',
        ],
        'password' => [
            'confirmed' => 'Пароли не совпадают',
            'min' => 'Пароль должен содержать минимум :min символов',
        ],
        'first_name' => [
            'required' => 'Имя обязательно для заполнения',
        ],
        'last_name' => [
            'required' => 'Фамилия обязательна для заполнения',
        ],
        'phone' => [
            'required' => 'Телефон обязателен для заполнения',
        ],

    ],

    'attributes' => [
        'last_name'        => 'Фамилия', // Это у вас уже работает
        'name'             => 'Имя',
        'first_name'       => 'Имя',
        'appointment_time' => 'Время записи',
        'appointment_date' => 'Дата записи',
        'service_id'       => 'Услуга',
        'specialist_id'    => 'Мастер',
        'phone'            => 'Номер телефона',
    ],
];
