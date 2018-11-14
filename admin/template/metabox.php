<?php

namespace NikolayS93\Reviews;

use NikolayS93\WPAdminForm\Form as Form;

$form = new Form( Utils::get_fields('checkbox'), array(
    'is_table' => true,
    'sub_name' => 'fields',
) );

$form->display();

submit_button( __('Save'), 'primary right', 'save_changes' );
echo '<div class="clear"></div>';