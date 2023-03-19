<?php

Configure::set('Whmsonic.email_templates', [
    'en_us' => [
        'lang' => 'en_us',
        'text' => 'Dear {client.first_name},

Thank you for choosing us to be your hosting provider. This email contains the details and credentials you need to make use of your hosting account.

New Account Information

Control Panel Login https://{module.hostname}:{module.port}

Control Panel Username: {service.username}

Control Panel Password: {service.password}

Reset Password: https://{module.hostname}:{module.port}/index.php?page=login&action=forgot.password

If you have any questions, please don’t hesitate to let us know by emailing support@mydomain.com.

Thank you',
        'html' => '
<p>Dear {client.first_name},</p><p>Thank you for choosing us to be your hosting provider. This email contains the details and credentials you need to make use of your hosting account.</p><p><strong>New Account Information</strong></p><p>Control Panel Login https://{module.hostname}:{module.port}</p><p>Control Panel Username: {service.username}</p><p>Control Panel Password: {service.password}</p><p>Reset Password: https://{module.hostname}:{module.port}/index.php?page=login&amp;action=forgot.password</p><p>If you have any questions, please don’t hesitate to let us know by emailing support@mydomain.com.</p><p>Thank you</p><p><br data-cke-filler="true"></p><p><br data-cke-filler="true"></p>
'
    ]
]);
