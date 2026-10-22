<?php
/**
 * Single source of truth for per-restaurant mock data.
 * Every staff page AND the sidebar read from here, so they can never disagree.
 * Keys of each array are the exact restaurant names from staff_context.php.
 */
return [
    'reservations' => [
                'The Revolving Restaurant' => [
                    ['R-2201','18:30','Hassan, Ahmed',   2,'T-02','+20 100 234 5678','',                      'confirmed'],
                    ['R-2202','19:00','El-Sayed, Fatima',2,'T-06','+20 100 987 6543','Garlic allergy noted',   'confirmed'],
                    ['R-2203','19:30','Mansour, Omar',   4,'T-10','+20 101 555 4433','Window seat preferred',  'confirmed'],
                    ['R-2204','20:00','Karim, Leila',    3,'T-04','+20 102 111 2233','Business dinner',        'pending'],
                    ['R-2205','20:30','Ibrahim, Khaled', 6,'T-07','+20 100 778 8900','Birthday celebration',   'confirmed'],
                    ['R-2206','21:00','Naguib, Sara',    2,'T-01','+20 101 447 5566','',                       'confirmed'],
                ],
                'Le Petite Chef' => [
                    ['R-3101','18:00','El-Masry, Mostafa', 2,'T-01','+20 109 456 7812','Anniversary dinner',       'confirmed'],
                    ['R-3102','18:45','Ghanem, Nourhan',   4,'T-03','+20 106 321 4455','Allergy: gluten-free',     'confirmed'],
                    ['R-3103','19:15','Farouk, Sherif',    2,'T-05','+20 101 887 5522','VIP guest',                'pending'],
                    ['R-3104','20:00','Khattab, Salma',    3,'T-08','+20 100 665 9933','Table near animation',     'confirmed'],
                    ['R-3105','20:30','El-Kady, Tamer',    5,'T-09','+20 102 774 1188','Family celebration',       'confirmed'],
                    ['R-3106','21:15','Radwan, Dina',      2,'T-02','+20 103 992 3344','Quiet corner requested',   'confirmed'],
                ],
                'Mo Bistro' => [
        ['R-4001','17:30','Ali, Youssef',      2,'T-01','+20 100 111 9988','High chair required',      'confirmed'],
        ['R-4002','18:15','Soliman, Mariam',   4,'T-04','+20 102 333 4455','Birthday dessert plate',   'confirmed'],
        ['R-4003','19:00','Hamed, Ziad',       3,'T-03','+20 101 666 7788','',                         'pending'],
        ['R-4004','20:15','Abdellatif, Reem',  5,'T-08','+20 105 888 2211','Outdoor seating preference','confirmed'],
        ['R-4005','21:00','Fawzy, Karim',      2,'T-02','+20 109 444 3322','',                         'confirmed'],
    ]
            ],

    'tables' => [
        'The Revolving Restaurant' => [
            [1,2,'free',    ''],
            [2,4,'reserved','Hassan, A.'],
            [3,2,'occupied','El-Sayed, F.'],
            [4,6,'free',    ''],
            [5,4,'occupied','Mansour, O.'],
            [6,2,'reserved','Karim, L.'],
            [7,8,'free',    ''],
            [8,4,'occupied','Ibrahim, K.'],
            [9,2,'free',    ''],
            [10,4,'reserved','Naguib, S.'],
            [11,6,'occupied','Taha, M.'],
            [12,2,'free',   ''],
        ],
        'Le Petite Chef' => [
            [1,2,'reserved','El-Masry, M.'],
            [2,4,'free',    ''],
            [3,2,'occupied','Ghanem, N.'],
            [4,6,'free',    ''],
            [5,4,'reserved','Farouk, S.'],
            [6,2,'occupied','Khattab, S.'],
            [7,8,'free',    ''],
            [8,4,'occupied','El-Kady, T.'],
            [9,2,'free',    ''],
            [10,4,'reserved','Radwan, D.'],
            [11,6,'occupied','Fawzy, A.'],
            [12,2,'free',   ''],
        ],
        'Mo Bistro' => [
        [1,2,'occupied','Ali, Y.'],
        [2,4,'reserved','Fawzy, K.'],
        [3,2,'occupied','Hamed, Z.'],
        [4,6,'occupied','Soliman, M.'],
        [5,4,'free',    ''],
        [6,2,'free',    ''],
        [7,8,'free',    ''],
        [8,4,'reserved','Abdellatif, R.'],
        [9,2,'free',    ''],
        [10,4,'free',   ''],
        [11,6,'free',   ''],
        [12,2,'free',   ''],
    ]
    ],

    'requests' => [
        'The Revolving Restaurant' => [
            [
                'id' => 101,
                'table' => 3,
                'guest' => 'El-Sayed, Fatima',
                'status' => 'pending',
                'border_color' => '',
                'text' => 'Please no garlic in any dish — I have a serious intolerance. Thank you.',
                'time' => 'Received at 20:09',
                'tag' => null,
                'reply' => '',
            ],
            [
                'id' => 102,
                'table' => 3,
                'guest' => 'El-Sayed, Fatima',
                'status' => 'pending',
                'border_color' => 'var(--navy)',
                'text' => "It's our anniversary — could you set up the table with candles and a small arrangement before we arrive?",
                'time' => 'Received at 17:40',
                'tag' => ['icon' => 'fa-heart', 'name' => 'Anniversary', 'bg' => 'rgba(255,105,135,.1)', 'color' => '#C0415B'],
                'reply' => '',
            ],
            [
                'id' => 100,
                'table' => 8,
                'guest' => 'Ibrahim, Khaled',
                'status' => 'answered',
                'border_color' => '#2D7A5F',
                'text' => 'Could we get extra baladi bread with the meal, please?',
                'time' => 'Received at 18:10 · Answered at 18:15',
                'tag' => null,
                'reply' => 'Absolutely — extra bread is on its way. Enjoy your meal, Mr. Ibrahim!',
            ],
        ],
        'Le Petite Chef' => [
            [
                'id' => 201,
                'table' => 1,
                'guest' => 'El-Masry, Mostafa',
                'status' => 'pending',
                'border_color' => 'var(--navy)',
                'text' => "It's our anniversary dinner — could you please ensure the table projector angle is focused clearly on our plates? Thank you.",
                'time' => 'Received at 20:15',
                'tag' => ['icon' => 'fa-heart', 'name' => 'Anniversary', 'bg' => 'rgba(255,105,135,.1)', 'color' => '#C0415B'],
                'reply' => '',
            ],
            [
                'id' => 202,
                'table' => 3,
                'guest' => 'Ghanem, Nourhan',
                'status' => 'pending',
                'border_color' => '',
                'text' => 'Gluten allergy noted on reservation — please ensure the chef prepares gluten-free bread rolls for our table.',
                'time' => 'Received at 19:50',
                'tag' => ['icon' => 'fa-wheat-awn-circle-exclamation', 'name' => 'Dietary', 'bg' => 'rgba(201,169,110,.15)', 'color' => '#A8843E'],
                'reply' => '',
            ],
            [
                'id' => 200,
                'table' => 9,
                'guest' => 'El-Kady, Tamer',
                'status' => 'answered',
                'border_color' => '#2D7A5F',
                'text' => 'Could we have warm damp towels brought to the table before dessert?',
                'time' => 'Received at 18:30 · Answered at 18:34',
                'tag' => null,
                'reply' => 'Certainly, Mr. El-Kady — warm lemon-scented towels are being prepared for your table now.',
            ],
        ],
        'Mo Bistro' => [
        [
            'id' => 301,
            'table' => 4,
            'guest' => 'Soliman, Mariam',
            'status' => 'pending',
            'border_color' => '',
            'text' => 'Could we please get extra toasted almond flakes with the almond soup?',
            'time' => 'Received at 20:10',
            'tag' => null,
            'reply' => '',
        ],
        [
            'id' => 300,
            'table' => 2,
            'guest' => 'Ali, Youssef',
            'status' => 'answered',
            'border_color' => '#2D7A5F',
            'text' => 'High chair needed for our toddler, please.',
            'time' => 'Received at 18:00 · Answered at 18:02',
            'tag' => null,
            'reply' => 'High chair has been placed at Table 2. Welcome!',
        ]
    ]
    ],

    'orders' => [
        'The Revolving Restaurant' => [
            'incoming' => [
                [
                    'id' => 1042,
                    'table' => 3,
                    'time' => '20:14',
                    'items' => [
                        ['qty' => 1, 'name' => 'Chicken Caesar Salad', 'price' => '600 EGP'],
                        ['qty' => 1, 'name' => 'Grilled Lobster', 'price' => '2500 EGP'],
                        ['qty' => 2, 'name' => 'Chocolate Fondant', 'price' => '800 EGP'],
                    ],
                    'total' => '3900 EGP',
                ],
                [
                    'id' => 1043,
                    'table' => 7,
                    'time' => '20:18',
                    'items' => [
                        ['qty' => 1, 'name' => 'Shrimp Bisque', 'price' => '700 EGP'],
                        ['qty' => 1, 'name' => 'Land & Sea', 'price' => '3600 EGP'],
                        ['qty' => 1, 'name' => 'Fine Apple Tart', 'price' => '375 EGP'],
                    ],
                    'total' => '4675 EGP',
                ],
            ],
            'active' => [
                [
                    'id' => 1038,
                    'table' => 5,
                    'time' => '19:52',
                    'step' => 1,
                    'status' => 'Preparing',
                    'items' => [
                        ['qty' => 1, 'name' => 'Baked Onion Soup', 'price' => '570 EGP'],
                        ['qty' => 2, 'name' => 'Rib Eye', 'price' => '3000 EGP'],
                        ['qty' => 2, 'name' => 'Creme Brulee', 'price' => '650 EGP'],
                    ],
                    'total' => '4220 EGP',
                ],
                [
                    'id' => 1040,
                    'table' => 11,
                    'time' => '19:30',
                    'step' => 2,
                    'status' => 'Ready',
                    'items' => [
                        ['qty' => 2, 'name' => 'Truffle Mashed Potatoes', 'price' => '900 EGP'],
                        ['qty' => 1, 'name' => 'Mushroom Risotto', 'price' => '500 EGP'],
                    ],
                    'total' => '1400 EGP',
                ],
            ],
        ],
        'Le Petite Chef' => [
            'incoming' => [
                [
                    'id' => 2081,
                    'table' => 2,
                    'time' => '20:22',
                    'items' => [
                        ['qty' => 1, 'name' => 'French Onion Soup', 'price' => '550 EGP'],
                        ['qty' => 1, 'name' => 'Pan-Seared Sea Bass', 'price' => '2100 EGP'],
                        ['qty' => 2, 'name' => 'Vanilla Crème Brûlée', 'price' => '700 EGP'],
                    ],
                    'total' => '3350 EGP',
                ],
                [
                    'id' => 2084,
                    'table' => 5,
                    'time' => '20:30',
                    'items' => [
                        ['qty' => 1, 'name' => 'Shrimp Cocktail', 'price' => '680 EGP'],
                        ['qty' => 1, 'name' => 'Wagyu Beef Tenderloin', 'price' => '3800 EGP'],
                        ['qty' => 1, 'name' => 'Chocolate Soufflé', 'price' => '600 EGP'],
                    ],
                    'total' => '5080 EGP',
                ],
            ],
            'active' => [
                [
                    'id' => 2077,
                    'table' => 4,
                    'time' => '19:48',
                    'step' => 1,
                    'status' => 'Preparing',
                    'items' => [
                        ['qty' => 1, 'name' => 'Wild Mushroom Cappuccino', 'price' => '580 EGP'],
                        ['qty' => 2, 'name' => 'Duck Confit', 'price' => '2600 EGP'],
                        ['qty' => 2, 'name' => 'Tarte Tatin', 'price' => '720 EGP'],
                    ],
                    'total' => '3900 EGP',
                ],
                [
                    'id' => 2079,
                    'table' => 8,
                    'time' => '19:35',
                    'step' => 2,
                    'status' => 'Ready',
                    'items' => [
                        ['qty' => 1, 'name' => 'Truffle Risotto', 'price' => '950 EGP'],
                        ['qty' => 1, 'name' => 'Grilled Salmon Fillet', 'price' => '1800 EGP'],
                    ],
                    'total' => '2750 EGP',
                ],
            ],
        ],
        'Mo Bistro' => [
        'incoming' => [
            [
                'id' => 3012,
                'table' => 4,
                'time' => '20:05',
                'items' => [
                    ['qty' => 1, 'name' => 'Lamb Shank', 'price' => '890 EGP'],
                    ['qty' => 1, 'name' => 'Mo\'s Bistro Almond Soup', 'price' => '195 EGP'],
                    ['qty' => 1, 'name' => 'Tiramisu', 'price' => '290 EGP'],
                ],
                'total' => '1375 EGP',
            ]
        ],
        'active' => [
            [
                'id' => 3008,
                'table' => 2,
                'time' => '19:40',
                'step' => 1,
                'status' => 'Preparing',
                'items' => [
                    ['qty' => 2, 'name' => 'Mushroom Risotto', 'price' => '1040 EGP'],
                    ['qty' => 1, 'name' => 'Steak Flank', 'price' => '780 EGP'],
                ],
                'total' => '1820 EGP',
            ]
        ]
    ]
    ],

];