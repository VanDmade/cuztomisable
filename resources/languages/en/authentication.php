<?php

return [
    'emails' => [
        'subjects' => [
            'mfa' => 'Multi-Factor Authentication',
            'forgot' => 'Forgot Password',
            'reset' => 'Reset Password',
            'new_ip_address' => 'New Login Detected',
            'email_verification' => 'Email Verification',
        ],
    ],
    'login' => [
        'logged_in' => 'You have logged in.',
        'logged_out' => 'You have logged out.',
        'errors' => [
            'invalid_credentials' => 'The credentials are invalid.',
            'attempts' => 'Your account is currently disabled due to too many attempts. Please come back later.',
            'verification' => [
                'email_required' => 'Please verify your email address before continuing.',
                'phone_required' => 'Please verify your phone number before continuing.',
            ],
        ],
    ],
    'mfa' => [
        'sent' => 'The code was :sent to :location.',
        'errors' => [
            'not_created' => 'There was an error while creating your MFA code.',
            'not_found' => 'The code was not found.',
            'sent_recently' => 'The code was recently resent. Try again later and check your spam/junk folder.',
            'expired' => 'The code has expired.',
        ],
    ],
    'passwords' => [
        'sent' => 'The password reset code was sent.',
        'resent' => 'The password reset code was resent.',
        'reset' => 'The password was reset.',
        'errors' => [
            'not_found' => 'The code was not found.',
            'already_sent' => 'The code was already sent.',
            'sent_recently' => 'The code was recently resent. Try again later and check your spam/junk folder.',
            'used_recently' => 'The password was used recently. Please use a new password.',
        ],
    ],
    'registration' => [
        'verified' => 'The code has been verified.',
        'created' => 'Welcome to the application!',
        'errors' => [
            'not_found' => 'The code entered was not found.',
            'used' => 'The registration code was already used.',
            'expired' => 'The registration code has already expired. Please contact an administrator.',
        ],
    ],
];