<?php
return [
    'top-up' => [
        'field_name' => [
            'title' => 'text',
        ],
        'validation' => [
            'title.*' => 'required|max:100',
        ]
    ],

    'voucher' => [
        'field_name' => [
            'title' => 'text',
        ],
        'validation' => [
            'title.*' => 'required|max:100',
        ]
    ],

    'gift-card' => [
        'field_name' => [
            'title' => 'text',
        ],
        'validation' => [
            'title.*' => 'required|max:100',
        ]
    ],


    'about-us' => [
        'field_name' => [
            'title' => 'text',
            'short_description' => 'textarea',
            'button_name' => 'text',
            'button_link' => 'url',
            'image' => 'file'
        ],
        'validation' => [
            'title.*' => 'required|max:100',
            'short_description.*' => 'required|max:2000',
            'button_name.*' => 'required|max:2000',
            'button_link.*' => 'required|max:2000',
            'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
        ],
        'size' => [
            'image' => '614x721'
        ]
    ],

    'why-choose-us' => [
        'field_name' => [
            'title' => 'text',
            'button_name' => 'text',
            'button_link' => 'url',
            'image' => 'file'
        ],
        'validation' => [
            'title.*' => 'required|max:100',
            'button_name.*' => 'required|max:2000',
            'button_link.*' => 'required|max:2000',
            'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
        ],
        'size' => [
            'image' => '614x721'
        ]
    ],


    'faq' => [
        'field_name' => [
            'title' => 'text',
            'image' => 'file'

        ],
        'validation' => [
            'title.*' => 'required|max:100',
            'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
        ],
        'size' => [
            'image' => '614x721'
        ]
    ],

    'sell-post' => [
        'field_name' => [
            'title' => 'text',
        ],
        'validation' => [
            'title.*' => 'required|max:100',
        ]
    ],


    'blog' => [
        'field_name' => [
            'title' => 'text',
        ],
        'validation' => [
            'title.*' => 'required|max:100',
        ]
    ],

    'contact-us' => [
        'field_name' => [
            'heading' => 'text',
            'sub_heading' => 'text',
            'email' => 'text',
            'phone' => 'text',
            'address' => 'text',
            'footer_short_details' => 'textarea'
        ],
        'validation' => [
            'heading.*' => 'required|max:100',
            'sub_heading.*' => 'required|max:1000',
            'email.*' => 'required|max:2000',
            'address.*' => 'required|max:2000',
            'phone.*' => 'required|max:2000'
        ]
    ],
    'message' => [
        'required' => 'This field is required.',
        'min' => 'This field must be at least :min characters.',
        'max' => 'This field may not be greater than :max characters.',
        'image' => 'This field must be image.',
        'mimes' => 'This image must be a file of type: jpg, jpeg, png.',
    ],
    'template_media' => [
        'image' => 'file',
        'thumbnail' => 'file',
        'youtube_link' => 'url',
        'button_link' => 'url',
    ]
];
