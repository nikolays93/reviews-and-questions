<?php

/**
 * @todo : review form shortcode
 * @todo : editble forms
 */
class DT_Reviews_Questions_RAQ extends DT_Reviews_Questions
{
  function __construct()
  {
      parent::$tabs[] = array('RAQ_TAB', array($this, 'render_tab'), 'Reviews And Questions');
  }

  function render_tab(){
    echo "Эта страница в разработке";
  }

}
new DT_Reviews_Questions_RAQ();