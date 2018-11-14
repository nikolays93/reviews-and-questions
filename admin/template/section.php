<?php

namespace NikolayS93\Reviews;

$code = '';

$arActive = Utils::get('fields');
if( !empty($arActive) ) {
    $active = array_keys($arActive);
    $fields = Utils::get_fields();

    foreach ($fields as $field) {
        if( !in_array($field['id'], $active) ) continue;

        $code .= "[{$field['type']} {$field['id']} class:form-control placeholder \"{$field['label']}\"]\n";
    }

    $code .= "\n";
    $code .= '[textarea your-message class:form-control x6 placeholder "Ваше сообщение"]' . "\n";
    $code .= '[hidden '.Plugin::HOOK.' class:hidden "leave message"]' . "\n";
    $code .= '[submit class:btn class:btn-primary "Отправить"]';
}

if( !$code ) {
    echo "Установите нужные параметры и сохраните изменения";
}
else {
    ?>
    <p><label for='wpcf-template'>Вставьте этот код в шаблон формы 'Contact Form 7' для создания формы отправки сообщения: </label></p>
    <div class="postbox-container normal-container">
        <div class="postbox">
            <h2 class="handle"><span>WP Contact Form 7 Code</span></h2>
            <div class="inside">
                <?php echo "<textarea id='wpcf-template' class='widefat' rows=8>".esc_html( $code )."</textarea>"; ?>
            </div>
        </div>
    </div>
    <?php
}

printf( '<input type="hidden" name="page" value="%s" />', $_REQUEST['page'] );