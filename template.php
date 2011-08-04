<?php
// $Id$
/**
 * @file
 * Contains theme override functions and preprocess functions for the Soda theme.
 */

/**
 * Implements hook_html_head_alter().
 * We are overwriting the default meta character type tag with HTML5 version.
 */
function html5_mobile_html_head_alter(&$head_elements) {
  $head_elements['system_meta_content_type']['#attributes'] = array(
    'charset' => 'utf-8'
  );   
}

/**
 * Changes the search form to use the "search" input element of HTML5.
 */
function html5_mobile_preprocess_search_block_form(&$variables) {
  $variables['search_form'] = str_replace('type="text"', 'type="search"', $variables['search_form']);
} 


function html5_mobile_preprocess_html(&$variables, $hook) {   
 	// Classes for body element. Allows advanced theming based on context
  // (home page, node of certain type, etc.)
  if (!$variables['is_front']) {
    // Add unique class for each page.
    $path = drupal_get_path_alias($_GET['q']);
    // Add unique class for each website section.
    list($section, ) = explode('/', $path, 2);
    if (arg(0) == 'node') {
      if (arg(1) == 'add') {
        $section = 'node-add';
      }
      elseif (is_numeric(arg(1)) && (arg(2) == 'edit' || arg(2) == 'delete')) {
        $section = 'node-' . arg(2);
      }
    }
    $variables['classes_array'][] = drupal_html_class('section-' . $section);
    $variables['classes_array'][] = drupal_html_class('path-' . $path);
  }
}
function html5_mobile_preprocess_page(&$variables, $hook) { 
   	//$variables['subtitle'] = $node->field_subtitle['und'][0]['safe_value']; 
  // Adding classes whether #navigation is here or not
  if (!empty($variables['main_menu']) or !empty($variables['sub_menu'])) {
    $variables['classes_array'][] = 'with-navigation';
  }
  if (!empty($variables['secondary_menu'])) {
    $variables['classes_array'][] = 'with-subnav';
  }
 // Add template suggestions based on content type 
	if (isset($variables['node'])) {  
	//	$variables['theme_hook_suggestions'][] = 'page__type--'. str_replace('_', '--', $variables['node']->type);
    $variables['theme_hook_suggestions'][] = 'page__type__'. $variables['node']->type;
		$variables['theme_hook_suggestions'][] = "page__node__" . $variables['node']->nid; 
  }
} 


function html5_mobile_preprocess_node(&$variables) {
  // Add a striping class. 
  $variables['classes_array'][] = 'node-' . $variables['zebra'];
  if (!$variables['status']) {
    $variables['classes_array'][] = 'node-unpublished';
    $variables['unpublished'] = TRUE;
  }
  else {
    $variables['unpublished'] = FALSE;
  }
}

function html5_mobile_preprocess_block(&$variables, $hook) {
  // Add a striping class.
  $variables['classes_array'][] = 'block-' . $variables['zebra'];
}

/**
 * Return a themed breadcrumb trail.
 *
 * @param $breadcrumb
 *   An array containing the breadcrumb links.
 * @return
 *   A string containing the breadcrumb output.
 */
function html5_mobile_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];
  // Determine if we are to display the breadcrumb.
  $show_breadcrumb = theme_get_setting('html5_mobile_breadcrumb');
  if ($show_breadcrumb == 'yes' || $show_breadcrumb == 'admin' && arg(0) == 'admin') {

    // Optionally get rid of the homepage link.
    $show_breadcrumb_home = theme_get_setting('html5_mobile_breadcrumb_home');
    if (!$show_breadcrumb_home) {
      array_shift($breadcrumb);
    }

    // Return the breadcrumb with separators.
    if (!empty($breadcrumb)) {
      $breadcrumb_separator = theme_get_setting('html5_mobile_breadcrumb_separator');
      $trailing_separator = $title = '';
      if (theme_get_setting('html5_mobile_breadcrumb_title')) {
        if ($title = drupal_get_title()) {
          $trailing_separator = $breadcrumb_separator;
        }
      }
      elseif (theme_get_setting('html5_mobile_breadcrumb_trailing')) {
        $trailing_separator = $breadcrumb_separator;
      }
      return '<div class="breadcrumb">' . implode($breadcrumb_separator, $breadcrumb) . "$trailing_separator$title</div>";
    }
  }
  // Otherwise, return an empty string.
  return '';
}

/* 	
 * 	Converts a string to a suitable html ID attribute.
 * 	
 * 	 http://www.w3.org/TR/html4/struct/global.html#h-7.5.2 specifies what makes a
 * 	 valid ID attribute in HTML. This function:
 * 	
 * 	- Ensure an ID starts with an alpha character by optionally adding an 'n'.
 * 	- Replaces any character except A-Z, numbers, and underscores with dashes.
 * 	- Converts entire string to lowercase.
 * 	
 * 	@param $string
 * 	  The string
 * 	@return
 * 	  The converted string
 */	


function html5_mobile_id_safe($string) {
  // Replace with dashes anything that isn't A-Z, numbers, dashes, or underscores.
  $string = strtolower(preg_replace('/[^a-zA-Z0-9_-]+/', '-', $string));
  // If the first character is not a-z, add 'n' in front.
  if (!ctype_lower($string{0})) { // Don't use ctype_alpha since its locale aware.
    $string = 'id'. $string;
  }
  return $string;
}

/**
 * Generate the HTML output for a menu link and submenu.
 *
 * @param $variables
 *   An associative array containing:
 *   - element: Structured array data for a menu link.
 *
 * @return
 *   A themed HTML string.
 *
 * @ingroup themeable
 */
 
function html5_mobile_menu_link(array $variables) {
  $element = $variables['element'];
  $sub_menu = '';

  if ($element['#below']) {
    $sub_menu = drupal_render($element['#below']);
  }
  $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  // Adding a class depending on the TITLE of the link (not constant)
  $element['#attributes']['class'][] = html5_mobile_id_safe($element['#title']);
  // Adding a class depending on the ID of the link (constant)
  $element['#attributes']['class'][] = 'mid-' . $element['#original_link']['mlid'];
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}

/* 	
 * 	Customize the PRIMARY and SECONDARY LINKS, to allow the admin tabs to work on all browsers
 */ 	

function html5_mobile_menu_local_task($variables) {
  $link = $variables['element']['#link'];
  $link['localized_options']['html'] = TRUE;
  return '<li' . (!empty($variables['element']['#active']) ? ' class="active"' : '') . '>' . l('<span class="tab">' . $link['title'] . '</span>', $link['href'], $link['localized_options']) . "</li>\n";
}

/*
 *  Duplicate of theme_menu_local_tasks() but adds clearfix to tabs.
 */

function html5_mobile_menu_local_tasks() {
  $output = array();
  if ($primary = menu_primary_local_tasks()) {
    if(menu_secondary_local_tasks()) {
      $primary['#prefix'] = '<ul class="tabs primary with-secondary clearfix">';
    }
    else {
      $primary['#prefix'] = '<ul class="tabs primary clearfix">';
    }
    $primary['#suffix'] = '</ul>';
    $output[] = $primary;
  }
  if ($secondary = menu_secondary_local_tasks()) {
    $secondary['#prefix'] = '<ul class="tabs secondary clearfix">';
    $secondary['#suffix'] = '</ul>';
    $output[] = $secondary;
  }
  return drupal_render($output);
}