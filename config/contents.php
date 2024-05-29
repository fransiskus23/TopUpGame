<?php
return [
    'slider' => [
        'field_name' => [
            'title' => 'text',
            'sub_title' => 'text',
            'button_name' => 'text',
            'button_link' => 'url',
            'image' => 'file',
        ],
        'validation' => [
            'title.*' => 'required|max:100',
            'button_name.*' => 'required|max:2000',
            'button_link.*' => 'required|max:2000',
            'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
        ],
        'size' => [
            'image' => '2650x1440'
        ]
    ],


    'why-choose-us' => [
        'field_name' => [
            'title' => 'text',
            'information' => 'textarea',
            'image' => 'file',
        ],
        'validation' => [
            'title.*' => 'required|max:100',
            'information.*' => 'required|max:300',
            'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
        ],
        'size' => [
            'image' => '50x50'
        ]
    ],

    'faq' => [
        'field_name' => [
            'title' => 'text',
            'description' => 'textarea',

        ],
        'validation' => [
            'title.*' => 'required|max:100',
            'description.*' => 'required|max:3000'
        ]
    ],


    'statistics' => [
        'field_name' => [
            'title' => 'text',
            'number' => 'text',
        ],
        'validation' => [
            'title.*' => 'required|max:100',
            'number.*' => 'required|min:1',
        ],
    ],

    'whats-clients-say' => [
        'field_name' => [
            'name' => 'text',
            'designation' => 'text',
            'description' => 'textarea',
            'image' => 'file',
        ],
        'validation' => [
            'name.*' => 'required|max:100',
            'designation.*' => 'required|max:100',
            'description.*' => 'required|max:3000',
            'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
        ],
        'size' => [
            'image' => '50x50'
        ]
    ],

    'blog' => [
        'field_name' => [
            'title' => 'text',
            'date_time' => 'text',
            'description' => 'textarea',
            'image' => 'file',
        ],
        'validation' => [
            'title.*' => 'required|max:100',
            'data_time.*' => 'required|max:100',
            'description.*' => 'required|max:30000',
            'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
        ]
    ],

    'support' => [
        'field_name' => [
            'title' => 'text',
            'description' => 'textarea'
        ],
        'validation' => [
            'title.*' => 'required|max:100',
            'description.*' => 'required|max:30000'
        ]
    ],
    'social' => [
        'field_name' => [
            'name' => 'text',
            'icon' => 'icon',
            'link' => 'url',
        ],
        'validation' => [
            'name.*' => 'required|max:100',
            'icon.*' => 'required|max:100',
            'link.*' => 'required|max:100'
        ],
    ],

    'message' => [
        'required' => 'This field is required.',
        'min' => 'This field must be at least :min characters.',
        'max' => 'This field may not be greater than :max characters.',
        'image' => 'This field must be image.',
        'mimes' => 'This image must be a file of type: jpg, jpeg, png.',
        'integer' => 'This field must be an integer value',
    ],

    'content_media' => [
        'image' => 'file',
        'thumbnail' => 'file',
        'youtube_link' => 'url',
        'button_link' => 'url',
        'link' => 'url',
        'icon' => 'icon'
    ]

];
