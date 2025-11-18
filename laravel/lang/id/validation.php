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

    'accepted' => 'Kolom :attribute harus disetujui.',
    'accepted_if' => 'Kolom :attribute harus disetujui ketika :other adalah :value.',
    'active_url' => 'Kolom :attribute harus berupa sebuah URL.',
    'after' => 'Kolom :attribute harus berupa tanggal setelah :date.',
    'after_or_equal' => 'Kolom :attribute harus berupa tanggal setelah atau sama dengan :date.',
    'alpha' => 'Kolom :attribute hanya berupa huruf.',
    'alpha_dash' => 'Kolom :attribute hanya berupa huruf, angka, dash (-), garis bawah (_).',
    'alpha_num' => 'Kolom :attribute hanya berupa huruf dan angka.',
    'any_of' => 'Kolom :attribute tidak valid.',
    'array' => 'Kolom :attribute field must be an array.',
    'ascii' => 'Kolom :attribute field must only contain single-byte alphanumeric characters and symbols.',
    'before' => 'Kolom :attribute field must be a date before :date.',
    'before_or_equal' => 'Kolom :attribute field must be a date before or equal to :date.',
    'between' => [
        'array' => 'Kolom :attribute field must have between :min and :max items.',
        'file' => 'Kolom :attribute field must be between :min and :max kilobytes.',
        'numeric' => 'Kolom :attribute field must be between :min and :max.',
        'string' => 'Kolom :attribute field must be between :min and :max characters.',
    ],
    'boolean' => 'Kolom :attribute field must be true or false.',
    'can' => 'Kolom :attribute field contains an unauthorized value.',
    'confirmed' => 'Kolom :attribute field confirmation does not match.',
    'contains' => 'Kolom :attribute field is missing a required value.',
    'current_password' => 'Kolom password is incorrect.',
    'date' => 'Kolom :attribute field must be a valid date.',
    'date_equals' => 'Kolom :attribute field must be a date equal to :date.',
    'date_format' => 'Kolom :attribute field must match the format :format.',
    'decimal' => 'Kolom :attribute field must have :decimal decimal places.',
    'declined' => 'Kolom :attribute field must be declined.',
    'declined_if' => 'Kolom :attribute field must be declined when :other is :value.',
    'different' => 'Kolom :attribute field and :other must be different.',
    'digits' => 'Kolom :attribute field must be :digits digits.',
    'digits_between' => 'Kolom :attribute field must be between :min and :max digits.',
    'dimensions' => 'Kolom :attribute field has invalid image dimensions.',
    'distinct' => 'Kolom :attribute field has a duplicate value.',
    'doesnt_contain' => 'Kolom :attribute field must not contain any of the following: :values.',
    'doesnt_end_with' => 'Kolom :attribute field must not end with one of the following: :values.',
    'doesnt_start_with' => 'Kolom :attribute field must not start with one of the following: :values.',
    'email' => 'Kolom :attribute field must be a valid email address.',
    'ends_with' => 'Kolom :attribute field must end with one of the following: :values.',
    'enum' => 'Kolom selected :attribute is invalid.',
    'exists' => 'Kolom selected :attribute is invalid.',
    'extensions' => 'Kolom :attribute field must have one of the following extensions: :values.',
    'file' => 'Kolom :attribute field must be a file.',
    'filled' => 'Kolom :attribute field must have a value.',
    'gt' => [
        'array' => 'Kolom :attribute field must have more than :value items.',
        'file' => 'Kolom :attribute field must be greater than :value kilobytes.',
        'numeric' => 'Kolom :attribute field must be greater than :value.',
        'string' => 'Kolom :attribute field must be greater than :value characters.',
    ],
    'gte' => [
        'array' => 'Kolom :attribute field must have :value items or more.',
        'file' => 'Kolom :attribute field must be greater than or equal to :value kilobytes.',
        'numeric' => 'Kolom :attribute field must be greater than or equal to :value.',
        'string' => 'Kolom :attribute field must be greater than or equal to :value characters.',
    ],
    'hex_color' => 'Kolom :attribute field must be a valid hexadecimal color.',
    'image' => 'Kolom :attribute field must be an image.',
    'in' => 'Kolom selected :attribute is invalid.',
    'in_array' => 'Kolom :attribute field must exist in :other.',
    'in_array_keys' => 'Kolom :attribute field must contain at least one of the following keys: :values.',
    'integer' => 'Kolom :attribute field must be an integer.',
    'ip' => 'Kolom :attribute field must be a valid IP address.',
    'ipv4' => 'Kolom :attribute field must be a valid IPv4 address.',
    'ipv6' => 'Kolom :attribute field must be a valid IPv6 address.',
    'json' => 'Kolom :attribute field must be a valid JSON string.',
    'list' => 'Kolom :attribute field must be a list.',
    'lowercase' => 'Kolom :attribute field must be lowercase.',
    'lt' => [
        'array' => 'Kolom :attribute field must have less than :value items.',
        'file' => 'Kolom :attribute field must be less than :value kilobytes.',
        'numeric' => 'Kolom :attribute field must be less than :value.',
        'string' => 'Kolom :attribute field must be less than :value characters.',
    ],
    'lte' => [
        'array' => 'Kolom :attribute field must not have more than :value items.',
        'file' => 'Kolom :attribute field must be less than or equal to :value kilobytes.',
        'numeric' => 'Kolom :attribute field must be less than or equal to :value.',
        'string' => 'Kolom :attribute field must be less than or equal to :value characters.',
    ],
    'mac_address' => 'Kolom :attribute harus berupa alamat MAC yang valid.',
    'max' => [
        'array' => 'Kolom :attribute tidak boleh lebih dari :max item.',
        'file' => 'Kolom :attribute tidak boleh lebih dari :max kilobytes.',
        'numeric' => 'Kolom :attribute tidak boleh lebih dari :max.',
        'string' => 'Kolom :attribute tidak boleh lebih dari :max karakter.',
    ],
    'max_digits' => 'Kolom :attribute tidak boleh melebihi :max digit.',
    'mimes' => 'Kolom :attribute harus berupa tipe file: :values.',
    'mimetypes' => 'Kolom :attribute harus berupa tipe file: :values.',
    'min' => [
        'array' => 'Kolom :attribute memiliki minimal :min item.',
        'file' => 'Kolom :attribute memiliki minimal :min kilobytes.',
        'numeric' => 'Kolom :attribute memiliki minimal :min.',
        'string' => 'Kolom :attribute memiliki minimal :min karakter.',
    ],
    'min_digits' => 'Kolom :attribute harus memiliki minimal: :min digit.',
    'missing' => 'Kolom :attribute harus hilang.',
    'missing_if' => 'Kolom :attribute harus hilang jika kolom :other berisi :value.',
    'missing_unless' => 'Kolom :attribute hars hilang kecuali kolom :other berisi :value.',
    'missing_with' => 'Kolom :attribute harus hilang jika :values ada.',
    'missing_with_all' => 'Kolom :attribute harus hilang ketika kolom :values ada.',
    'multiple_of' => 'Kolom :attribute harus berupa kelipatan dari :value.',
    'not_in' => 'Kolom :attribute tidak valid.',
    'not_regex' => 'Kolom :attribute tidak sesuai format.',
    'numeric' => 'Kolom :attribute harus berupa angka.',
    'password' => [
        'letters' => 'Kolom :attribute harus memiliki setidaknya satu huruf.',
        'mixed' => 'Kolom :attribute harus memiliki setidaknya satu huruf besar dan satu huruf kecil.',
        'numbers' => 'Kolom :attribute harus memiliki setidaknya satu angka.',
        'symbols' => 'Kolom :attribute harus memiliki setidaknya satu simbol.',
        'uncompromised' => 'Kolom :attribute telah bocor. Tolong ganti kolom :attribute supaya berbeda.',
    ],
    'present' => 'Kolom :attribute harus ada.',
    'present_if' => 'Kolom :attribute harus ada ketika kolom :other berisi :value.',
    'present_unless' => 'Kolom :attribute harus ada kecuali kolom :other berisi :value.',
    'present_with' => 'Kolom :attribute harus ada ketika kolom :values ada.',
    'present_with_all' => 'Kolom :attribute harus ada ketika kolom :values ada.',
    'prohibited' => 'Kolom :attribute harus kosong.',
    'prohibited_if' => 'Kolom :attribute harus kosong jika kolom :other adalah :value.',
    'prohibited_if_accepted' => 'Kolom :attribute harus kosong jika kolom :other disetujui.',
    'prohibited_if_declined' => 'Kolom :attribute harus kosong jika kolom :other tidak disetujui.',
    'prohibited_unless' => 'Kolom :attribute harus kosong kecuali :values ada di kolom :other.',
    'prohibits' => 'Kolom :attribute harus diisi namun kolom :other harus kosong.',
    'regex' => 'Kolom :attribute harus sesuai format.',
    'required' => 'Kolom :attribute harus diisi.',
    'required_array_keys' => 'Kolom :attribute harus memiliki entri untuk: :values.',
    'required_if' => 'Kolom :attribute harus diisi ketika :other adalah :value.',
    'required_if_accepted' => 'Kolom :attribute harus diisi ketika kolom :other disetujui.',
    'required_if_declined' => 'Kolom :attribute harus diisi ketika kolom :other tidak disetujui.',
    'required_unless' => 'Kolom :attribute harus diisi kecuali :values ada di kolom :other.',
    'required_with' => 'Kolom :attribute harus diisi ketika :values ada.',
    'required_with_all' => 'Kolom :attribute harus diisi ketika :values ada.',
    'required_without' => 'Kolom :attribute harus diisi ketika :values tidak tersedia.',
    'required_without_all' => 'Kolom :attribute harus diisi ketika tidak ada :values yang tersedia.',
    'same' => 'Kolom :attribute harus sama dengan kolom :other.',
    'size' => [
        'array' => 'Kolom :attribute harus memiliki :size item.',
        'file' => 'Kolom :attribute harus berukuran :size kilobytes.',
        'numeric' => 'Kolom :attribute harus :size.',
        'string' => 'Kolom :attribute harus memiliki :size karakter.',
    ],
    'starts_with' => 'Kolom :attribute harus diawali dengan: :values.',
    'string' => 'Kolom :attribute harus berupa kata/kalimat.',
    'timezone' => 'Kolom :attribute harus berupa zona waktu yang valid.',
    'unique' => 'Kolom :attribute sudah ada.',
    'uploaded' => 'Kolom :attribute gagal diupload',
    'uppercase' => 'Kolom :attribute harus berupa huruf besar.',
    'url' => 'Kolom :attribute harus berupa URL yang valid.',
    'ulid' => 'Kolom :attribute harus berupa ULID yang valid.',
    'uuid' => 'Kolom :attribute harus berupa UUID yang valid.',

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
