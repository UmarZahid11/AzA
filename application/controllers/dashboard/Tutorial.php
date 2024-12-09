<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Tutorial
 */
class Tutorial extends MY_Controller
{
    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        if ($this->userid <= 0) {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_LOGIN));
            redirect(l(''));
        }
    }

    /**
     * Method index
     *
     * @return void
     */
    public function index()
    {
        global $config;

        $data = array();

        $tutorial_path = $config['base_path'] . TUTORIAL_PATH;

        $path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $tutorial_path;

        $data['files'] = $this->getDirContents($path, true);

        //
        $this->layout_data['title'] = 'Tutorials | ' . $this->layout_data['title'];
        //
        $this->load_view('index', $data);
    }

    /**
     * Method getDirContents
     *
     * @param string $dir
     * @param bool $return_files
     * @param &$results $results
     *
     * @return void
     */
    function getDirContents(string $dir, bool $return_files = false, &$results = array())
    {
        $files = scandir($dir);
        if ($return_files) {
            return $files;
        }
        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                $results[] = $path;
            } else if ($value != "." && $value != "..") {
                $this->getDirContents($path, $return_files, $results);
                $results[] = $path;
            }
        }

        return $results;
    }
}
