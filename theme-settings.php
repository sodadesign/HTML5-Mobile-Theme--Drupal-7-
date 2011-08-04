<?php
// $Id$


/**
 * Implementation of hook_form_FORM_ID_alter().
 * Adding extra settings to the general theme settings form.
 *
 * @param array $form
 * @param array $form_state
 */
function html5_mobile_form_system_theme_settings_alter(&$form, &$form_state) {
  $form['styles'] = array(
    '#type' => 'fieldset',
    '#title' => t('HTML5 Mobile  settings'),
    '#collapsible' => TRUE,
  );

  $form['styles']['show_breadcrumb'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show breadcrumb'),
    '#default_value' => theme_get_setting('show_breadcrumb'),
  );
}

