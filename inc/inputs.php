<?php

$review_fields = array(
    array(
        'id'    => 'your-name',
        'name'  => DT_Reviews_Questions::METANAME . '][your-name',
        'type'  => 'text',
        'label' => 'Имя',
    ),
    array(
        'id'    => 'your-phone',
        'name'  => DT_Reviews_Questions::METANAME . '][your-phone',
        'type'  => 'text',
        'label' => 'Телефон',
    ),
    array(
        'id'    => 'your-email',
        'name'  => DT_Reviews_Questions::METANAME . '][your-email',
        'type'  => 'text',
        'label' => 'Email',
    ),
    array(
        'id'    => 'your-city',
        'name'  => DT_Reviews_Questions::METANAME . '][your-city',
        'type'  => 'text',
        'label' => 'Город',
    ),
    array(
        'id' => 'your-review-rating',
        'name'  => DT_Reviews_Questions::METANAME . '][your-review-rating',
        'type' => 'text',
        'label' => 'Рэйтинг',
    ),
    array(
        'id'    => 'your-work',
        'name'  => DT_Reviews_Questions::METANAME . '][your-work',
        'type'  => 'text',
        'label' => 'Организация',
    ),
    array(
        'id'    => 'your-rank',
        'name'  => DT_Reviews_Questions::METANAME . '][your-rank',
        'type'  => 'text',
        'label' => 'Должность',
    ),
);
return $review_fields;