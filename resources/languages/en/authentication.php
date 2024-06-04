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
        'mfa_logged_in' => 'Please verify this login attempt.',
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
        'verified' => 'The request has been verified.',
        'errors' => [
            'not_created' => 'There was an error while creating your MFA code.',
            'not_found' => 'The code was not found.',
            'token_has_expired' => 'This request is no longer valid.',
            'sent_recently' => 'The code was recently resent. Try again later and check your spam/junk folder.',
            'expired' => 'The code has expired.',
            'ip_address_not_found' => 'Something has occurred and the IP address doesn\'t match.',
        ],
    ],
    'passwords' => [
        'sent' => 'The password reset code was sent.',
        'resent' => 'The password reset code was resent.',
        'reset' => 'The password was reset.',
        'verified' => 'The reset token has been verified.',
        'errors' => [
            'not_found' => 'The code was not found.',
            'expired' => 'The reset code has expired. Please try again!',
            'invalid_code' => 'The code entered is invalid. Please try again!',
            'already_sent' => 'The code was already sent. Please try again later or click the link in the email.',
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