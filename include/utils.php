<?php

namespace NikolayS93\Reviews;

if ( ! defined( 'ABSPATH' ) )
  exit; // disable direct access

class Utils extends Plugin
{
    private function __construct() {}
    private function __clone() {}

    /**
     * Получает настройку из parent::$options || из кэша || из базы данных
     * @param  mixed  $default Что вернуть если опции не существует
     * @return mixed
     */
    private static function get_option( $default = array() )
    {
        if( ! parent::$options )
            parent::$options = get_option( parent::get_option_name(), $default );

        return apply_filters( "get_{DOMAIN}_option", parent::$options );
    }

    public static function get_post_type()
    {
        return apply_filters( "get_{DOMAIN}_post_type", parent::DEFAULT_POST_TYPE );
    }

    public static function get_meta_name()
    {
        return apply_filters( "get_{DOMAIN}_meta_name", parent::DEFAULT_META_NAME );
    }

    public static function get_fields( $type = '' )
    {
        $data = array(
            array(
                'id'    => 'your-name',
                'type'  => $type ? $type : 'text',
                'label' => __('Имя', DOMAIN),
            ),
            array(
                'id'    => 'your-phone',
                'type'  => $type ? $type : 'text',
                'label' => __('Телефон', DOMAIN),
            ),
            array(
                'id'    => 'your-email',
                'type'  => $type ? $type : 'email',
                'label' => __('Email', DOMAIN),
            ),
            array(
                'id'    => 'your-city',
                'type'  => $type ? $type : 'text',
                'label' => __('Город', DOMAIN),
            ),
            array(
                'id' => 'your-review-rating',
                'type' => $type ? $type : 'text',
                'label' => __('Рэйтинг', DOMAIN),
            ),
            array(
                'id'    => 'your-work',
                'type'  => $type ? $type : 'text',
                'label' => __('Организация', DOMAIN),
            ),
            array(
                'id'    => 'your-rank',
                'type'  => $type ? $type : 'text',
                'label' => __('Должность', DOMAIN),
            ),
        );

        return apply_filters( "get_{DOMAIN}_fields", $data );
    }

    /**
     * Получает url (адресную строку) до плагина
     * @param  string $path путь должен начинаться с / (по аналогии с __DIR__)
     * @return string
     */
    public static function get_plugin_url( $path = '' )
    {
        $url = plugins_url( basename(PLUGIN_DIR) ) . $path;

        return apply_filters( "get_{DOMAIN}_plugin_url", $url, $path );
    }

    public static function get_template( $template, $slug = false, $data = array() )
    {
        if ($slug) $templates[] = PLUGIN_DIR . '/' . $template . '-' . $slug;
        $templates[] = PLUGIN_DIR . '/' . $template;

        if ($tpl = locate_template($templates)) {
            return $tpl;
        }

        return false;
    }

    public static function get_admin_template( $tpl = '', $data = array(), $include = false )
    {
        $filename = PLUGIN_DIR . '/admin/template/' . $tpl;
        if( !file_exists($filename) ) $filename = false;

        if( $filename && $include ) {
            include $filename;
        }

        return $filename;
    }

    /**
     * Получает параметр из опции плагина
     * @todo Добавить фильтр
     *
     * @param  string  $prop_name Ключ опции плагина или 'all' (вернуть опцию целиком)
     * @param  mixed   $default   Что возвращать, если параметр не найден
     * @return mixed
     */
    public static function get( $prop_name, $default = false )
    {
        $option = self::get_option();
        if( 'all' === $prop_name ) {
            if( is_array($option) && count($option) ) {
                return $option;
            }

            return $default;
        }

        return isset( $option[ $prop_name ] ) ? $option[ $prop_name ] : $default;
    }

    /**
     * Установит параметр в опцию плагина
     * @todo Подумать, может стоит сделать $autoload через фильтр, а не параметр
     *
     * @param mixed  $prop_name Ключ опции плагина || array(параметр => значение)
     * @param string $value     значение (если $prop_name не массив)
     * @param string $autoload  Подгружать опцию автоматически @see update_option()
     * @return bool             Совершились ли обновления @see update_option()
     */
    public static function set( $prop_name, $value = '', $autoload = null )
    {
        $option = self::get_option();
        if( ! is_array($prop_name) ) $prop_name = array($prop_name => $value);

        foreach ($prop_name as $prop_key => $prop_value) {
            $option[ $prop_key ] = $prop_value;
        }

        return update_option( parent::get_option_name(), $option, $autoload );
    }
}
