<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'El campo :attribute debe ser aceptado.',
    'active_url' => 'El campo :attribute no es un URL válido.',
    'after' => 'El campo :attribute debe ser posterior a :date.',
    'after_or_equal' => 'El campo :attribute debe ser posterior a o igual a :date.',
    'alpha' => 'El campo :attribute solo puede contener letras.',
    'alpha_dash' => 'El campo :attribute solo puede contener letras, números, guiones y guiones bajos.',
    'alpha_num' => 'El campo :attribute solo puede contener letras y números.',
    'array' => 'El campo :attribute debe ser un arreglo.',
    'before' => 'El campo :attribute debe ser previo a :date.',
    'before_or_equal' => 'El campo :attribute debe ser a previo o igual a :date.',
    'between' => [
        'numeric' => 'El campo :attribute debe estar entre :min y :max.',
        'file' => 'El campo :attribute debe estar entre :min y :max kilobytes.',
        'string' => 'El campo :attribute debe estar entre :min y :max characters.',
        'array' => 'El campo :attribute must have between :min y :max items.',
    ],
    'boolean' => 'El campo :attribute debe ser verdadero o falso.',
    'confirmed' => 'El campo :attribute confirmación no es válido.',
    'date' => 'El campo :attribute no es una fecha válida.',
    'date_equals' => 'El campo :attribute debe ser una fecha igual a :date.',
    'date_format' => 'El campo :attribute con concuerda con el formato :format.',
    'different' => 'El campo :attribute y :other debe ser diferentes.',
    'digits' => 'El campo :attribute debe ser :digits digitos.',
    'digits_between' => 'El campo :attribute debe estar entre :min y :max digitos.',
    'dimensions' => 'El campo :attribute tiene dimensiones incorrectas.',
    'distinct' => 'El campo :attribute tiene un valor duplicado.',
    'email' => 'El campo :attribute debe ser un correo válido.',
    'ends_with' => 'El campo :attribute debe terminar con el valor: :values',
    'exists' => 'El campo :attribute es inválido o no existe.',
    'file' => 'El campo :attribute debe ser un archivo.',
    'filled' => 'El campo :attribute debe tener un valor/no debe ser vacío.',
    'gt' => [
        'numeric' => 'El campo :attribute debe ser mayor que :value.',
        'file' => 'El campo :attribute debe ser mayor que :value kilobytes.',
        'string' => 'El campo :attribute debe ser mayor que :value characters.',
        'array' => 'El campo :attribute debe tener al menos :value items.',
    ],
    'gte' => [
        'numeric' => 'El campo :attribute debe ser mayor que o igual a :value.',
        'file' => 'El campo :attribute debe ser mayor que o igual a :value kilobytes.',
        'string' => 'El campo :attribute debe ser mayor que o igual a :value characters.',
        'array' => 'El campo :attribute debe tener al menos :value items o más.',
    ],
    'image' => 'El campo :attribute debe ser una imagen.',
    'in' => 'El campo selected :attribute es inválido.',
    'in_array' => 'El campo :attribute no existe en :other.',
    'integer' => 'El campo :attribute debe ser un entero.',
    'ip' => 'El campo :attribute debe ser un valor válido de tipo IP address.',
    'ipv4' => 'El campo :attribute debe ser un valor válido de tipo IPv4 address.',
    'ipv6' => 'El campo :attribute debe ser un valor válido de tipo IPv6 address.',
    'json' => 'El campo :attribute debe ser un valor válido de tipo JSON string.',
    'lt' => [
        'numeric' => 'El campo :attribute debe ser menor que :value.',
        'file' => 'El campo :attribute debe ser menor que :value kilobytes.',
        'string' => 'El campo :attribute debe ser menor que :value characters.',
        'array' => 'El campo :attribute debe ser menor que :value items.',
    ],
    'lte' => [
        'numeric' => 'El campo :attribute debe ser menor que o igual a :value.',
        'file' => 'El campo :attribute debe ser menor que o igual a :value kilobytes.',
        'string' => 'El campo :attribute debe ser menor que o igual a :value characters.',
        'array' => 'El campo :attribute no puede ser mayor a :value items.',
    ],
    'max' => [
        'numeric' => 'El campo :attribute no puede ser mayor que :max.',
        'file' => 'El campo :attribute no puede ser mayor que :max kilobytes.',
        'string' => 'El campo :attribute no puede ser mayor que :max characters.',
        'array' => 'El campo :attribute no debe tener mas de :max items.',
    ],
    'mimes' => 'El campo :attribute debe ser un archivo de tipo: :values.',
    'mimetypes' => 'El campo :attribute debe ser un archivo de tipo: :values.',
    'min' => [
        'numeric' => 'El campo :attribute debe ser al menos :min.',
        'file' => 'El campo :attribute debe ser al menos :min kilobytes.',
        'string' => 'El campo :attribute debe ser al menos :min characters.',
        'array' => 'El campo :attribute debe tener al menos al menos :min items.',
    ],
    'not_in' => 'El campo selected :attribute es inválido.',
    'not_regex' => 'El campo :attribute format es inválido.',
    'numeric' => 'El campo :attribute debe ser un número.',
    'present' => 'El campo :attribute field debe ser presente.',
    'regex' => 'El campo :attribute tiene un formato que es inválido.',
    'required' => 'El campo :attribute es requerido.',
    'required_if' => 'El campo :attribute es requerido cuando :other es :value.',
    'required_unless' => 'El campo :attribute es requerido a menos que :other sea :values.',
    'required_with' => 'El campo :attribute es requerido cuando el valor :values no es vacío.',
    'required_with_all' => 'El campo :attribute es requerido cuando los valores :values no son vacíos.',
    'required_without' => 'El campo :attribute es requerido cuando los valores :values son vaciós.',
    'required_without_all' => 'El campo :attribute es requerido cuandon ninguno de los valores :values no son vacíos.',
    'same' => 'El campo :attribute y :other con coinciden.',
    'size' => [
        'numeric' => 'El campo :attribute debe ser :size.',
        'file' => 'El campo :attribute debe ser :size kilobytes.',
        'string' => 'El campo :attribute debe ser :size caracteres.',
        'array' => 'El campo :attribute debe contener :size items.',
    ],
    'starts_with' => 'El campo :attribute comenzar con algunos de los siguientes valores: :values',
    'string' => 'El campo :attribute debe ser texto.',
    'timezone' => 'El campo :attribute debe ser un valor válido de tipo zone.',
    'unique' => 'El campo :attribute ya exite y no se puede repetir.',
    'uploaded' => 'El campo :attribute falló al subirse.',
    'url' => 'El campo :attribute tiene un formato que es inválido.',
    'uuid' => 'El campo :attribute debe ser un valor válido de tipo UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
