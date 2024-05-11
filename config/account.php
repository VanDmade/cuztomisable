<?php

return [
    'passwords' => [
        'reset_with' => [
            'email' => true,
            'phone' => false,
        ],
        // Length of time between each allowed reset attempt in seconds
        'time_between_allowed_resets' => 900,
        // Specifies the length of time between allowed resends
        'resend_after' => 300,
        // The code is going to be recreated when the code is resent
        'recreate_code_on_resend' => false,
        /* Specifies that the reset code can be sent via phone and/or email.
         * If both set to false, the user's email will be used. */
        'send_via' => [
            'phone' => true,
            'email' => true,
        ],
        // The amount of passwords that need to be iterated through before using the same password again
        'reuse_after' => 3,
    ],
    'code' => [
        // Length of all codes sent to users
        'length' => 6,
        // Length of time the code will expire
        'expires_in' => 300,
    ],
    'registration' => [
        // Length of all codes sent to users
        'length' => 6,
        // Length of time the registration code will expire
        'expires_in' => 300,
    ],
    // Length of the token used within the URL, max length is 64
    'token_length' => 16,
    'notifications' => [
        /* Sets up the notifications within the system and how they are sent
         * type  email|phone, this value can be set to email, phone, or email|phone.
                  If email|phone, then the user shall have the ability to choose
                  whether the email/text is used to send the notfication
         * view  Content for the email|text */
        'reset' => [
            'type' => 'email',
            'view' => 'cuztomisable.emails.authentication.reset',
        ],
        'forgot' => [
            'type' => 'email',
            'view' => 'cuztomisable.emails.authentication.forgot',
        ],
        'email_verification' => [
            'type' => 'email',
            'view' => 'cuztomisable.emails.authentication.verifications.email',
        ],
        'phone_verification' => [
            'type' => 'phone',
            'view' => 'cuztomisable.emails.authentication.verifications.phone',
        ],
    ],
];