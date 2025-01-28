<?php

return [
    'required' => ':attribute فیلڈ خالی نہیں ہو سکتی۔',
    'email' => ':attribute درست ای میل ایڈریس ہونا چاہیے۔',
    'numeric' => ':attribute میں صرف اعداد ہونے چاہیے۔',
    'min' => [
        'string' => ':attribute کم از کم :min حروف کا ہونا ضروری ہے۔',
        'numeric' => ':attribute کم از کم :min ہونا چاہیے۔',
    ],
    'max' => [
        'string' => ':attribute زیادہ سے زیادہ :max حروف کا ہو سکتا ہے۔',
        'numeric' => ':attribute زیادہ سے زیادہ :max ہو سکتا ہے۔',
    ],
    'unique' => ':attribute پہلے سے موجود ہے۔',
    'confirmed' => ':attribute کی تصدیق مماثل نہیں ہے۔',
    'password' => [
        'letters' => ':attribute میں کم از کم ایک حرف ہونا چاہیے۔',
        'numbers' => ':attribute میں کم از کم ایک عدد ہونا چاہیے۔',
        'symbols' => ':attribute میں کم از کم ایک علامت ہونا ضروری ہے۔',
        'mixed' => ':attribute میں کم از کم ایک بڑا اور ایک چھوٹا حرف ہونا ضروری ہے۔',
    ],
    'string' => ':attribute حروف کی سلسلہ ہونا چاہیے۔',
    'date' => ':attribute درست تاریخ ہونی چاہیے۔',

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'مخصوص پیغام',
        ],
    ],

    'digits' => 'فیلڈ :attribute میں بالکل :digits ڈیجٹس ہونی چاہئیں۔',

    'attributes' => [],
];