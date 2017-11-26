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
		'id'    => 'your_city',
		'name'  => DT_Reviews_Questions::METANAME . '][your_city',
		'type'  => 'text',
		'label' => 'Город',
		),
    array(
        'id'    => 'your_rank',
        'name'  => DT_Reviews_Questions::METANAME . '][your_rank',
        'type'  => 'text',
        'label' => 'Должность',
        ),
        // array(
        //  'id' => 'your_review_rating',
        //  'type' => 'text',
        //  'label' => 'Рэйтинг',
        //  ),
	array(
		'id'    => 'your-work',
		'name'  => DT_Reviews_Questions::METANAME . '][your-work',
		'type'  => 'text',
		'label' => 'Организация',
		),
        // array(
        //  'id' => 'your-custom',
        //  'type' => 'text',
        //  'label' => '',
        //  ),
        // array(
        //  'id' => 'your-custom2',
        //  'type' => 'text',
        //  'label' => '',
        //  )
	);
return $review_fields;