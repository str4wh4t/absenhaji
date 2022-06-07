<?php
use Jenssegers\Blade\Blade;

function render($view, array $data = [])
{
    $views_dir_path = VIEWPATH;
    $cache_dir_path = APPPATH . DIRECTORY_SEPARATOR . 'cache';
    $blade = new Blade($views_dir_path, $cache_dir_path);

    $ci = &get_instance();
    $data['session'] = $ci->session->userdata();

    echo $blade->make($view, $data)->render();
}