<?php
/**
 * return a form tag
 */
function form_start($params = array()) {
	$r = '<form method="post" action="'.h($params['action']).'">';
	if ($params['method'] &&
	   	in_array($params['method'], array('put', 'delete'))) {

		$r .= '<input type="hidden" class="hidden" '.
			'name="_method" value="'.$params['method'].'"/>';
	}
	return $r;
}

/**
 * @return $flash
 */
function flasher()
{
  $str = '';
  if (!empty($_SESSION['flash'])) {
    $type = (!empty($_SESSION['flash']['notice'])) ? 'notice' : 'alert';
    if (!empty($_SESSION['flash'][$type])) {
      $str = '<div class='.$type.'>'.h($_SESSION['flash'][$type]).'</div>';
    }
    unset($_SESSION['flash']);
  }
  return $str;
}

