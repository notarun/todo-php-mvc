<?php

$validator->add('required', function ($input): ?string {
    return empty($input) ? 'This field is required' : null;
});

$validator->add('email', function ($input): ?string {
    return filter_var($input, FILTER_VALIDATE_EMAIL) ? null : 'Invalid email address.';
});