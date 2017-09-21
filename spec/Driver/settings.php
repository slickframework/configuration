<?php

/**
 * This file is part of Configuration
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Driver;

$settings = [];

$settings['foo'] = 'bar';

$settings['first'] = [
    'second' => [
        'third' => 123
    ]
];

return $settings;