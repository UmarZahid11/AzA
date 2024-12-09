<?php
$config['page_query_string'] = TRUE;
$config['use_page_numbers'] = TRUE;

$config['full_tag_open'] = '<ul class="pagination">';
$config['full_tag_close'] = '</ul>';
$config['num_tag_open'] = '<li>';
$config['num_tag_close'] = '</li>';

$config['last_tag_open'] = $config['first_tag_open'] = $config['prev_tag_open'] = $config['next_tag_open'] = $config['num_tag_open'] = '<li>';
$config['last_tag_close'] = $config['first_tag_close'] = $config['prev_tag_close'] = $config['next_tag_close'] = $config['num_tag_close'] = '</li>';

$config['first_tag_open'] = $config['prev_tag_open'] = $config['next_tag_open'] = $config['num_tag_open'] = '<li>';
$config['first_tag_close'] = $config['prev_tag_close'] = $config['next_tag_close'] = $config['num_tag_close'] = '</li>';

$config['cur_tag_open'] = '<li><a class="selected">';
$config['cur_tag_close'] = '</a></li>';
?>