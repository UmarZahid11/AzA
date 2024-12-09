<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Story
 */
class Story extends MY_Controller
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
     * Method save
     *
     * @return void
     */
    public function save(): void
    {
        if ($this->model_signup->hasPremiumPermission()) {
            if (isset($_POST)) {
                $type = 'insert';
                $error = false;
                $imageError = false;
                $errorMessage = __(ERROR_MESSAGE);
                $successMessage = "Success";
                $storyId = 0;
                $storyImage = "";
                $storyVideo = "";
                $affectStory = $_POST['story'];

                // check if story exists with the requested id
                if (isset($_POST['story_id']) && intVal($_POST['story_id']) > 0) {
                    $param = array();
                    $param['where']['story_id'] = $_POST['story_id'];
                    $param['where']['story_userid'] = $this->userid;
                    $storyDetail = $this->model_story->find_one($param);
                    if (empty($storyDetail)) {
                        $error = true;
                        $errorMessage = __("Requested story doesn't exists");
                    } else {
                        $storyId = $storyDetail['story_id'];
                        $storyImage = $storyDetail['story_image'];
                        $storyVideo = $storyDetail['story_video'];
                    }
                }

                if (!$error) {
                    
                    $param = array();
                    // exclude currently updating story for updation
                    if ($storyId) {
                        $param['where']['story_id !='] = $_POST['story_id'];
                    }
                    $param['where']['story_slug'] = $affectStory['story_slug'];
                    $storyDetail = $this->model_story->find_one($param);

                    if (empty($storyDetail)) {

                        // Get upload path
                        $upload_path = 'assets/uploads/story/';
                        $affectStory['story_image_path'] = $upload_path;
                        
                        if (($_FILES['file']['error'] == 0)) {
                            if($_FILES['file']['size'] < MAX_FILE_SIZE) {
                                // Get temp file
                                $tmp = $_FILES['file']['tmp_name'];
                                // Generate file name
                                $name = mt_rand() . $_FILES['file']['name'];
    
                                // Set data
                                $affectStory['story_image'] = $name;
    
                                // Remove old file
                                if ($storyImage) {
                                    unlink('assets/uploads/story/' . basename($storyImage));
                                }
    
                                // Upload new file
                                if (!move_uploaded_file($tmp, $upload_path . $name)) {
                                    $imageError = true;
                                }
                            } else {
                                $imageError = true;
                            }
                        }

                        if (($_FILES['story_video']['error'] == 0)) {
                            
                            if($_FILES['story_video']['size'] < MAX_FILE_SIZE) {
                                // Get temp file
                                $tmp = $_FILES['story_video']['tmp_name'];
                                // Generate file name
                                $name = mt_rand() . $_FILES['story_video']['name'];

                                // Set data
                                $affectStory['story_video'] = $name;

                                // Remove old file
                                if ($storyVideo) {
                                    unlink('assets/uploads/story/' . basename($storyVideo));
                                }

                                // Upload
                                if (!move_uploaded_file($tmp, $upload_path . $name)) {
                                    $videoError = true;
                                }
                            } else {
                                $videoError = true;
                            }
                        }
                            
                        // if action => update else, action => insert
                        if ($storyId) {
                            $inserted = $this->model_story->update_by_pk($storyId, $affectStory);

                            $type = 'update';
                            $successMessage = __('Changes saved!');

                            // if error
                            if (!$inserted) {
                                $error = true;
                                $errorMessage = __("Nothing to update!");
                            }
                        } else {
                            $inserted = $this->model_story->insert_record($affectStory);

                            $type = 'insert';
                            $successMessage = __('Story have been saved.');

                            // if error
                            if (!$inserted) {
                                $error = true;
                                $errorMessage = __("Nothing to update!");
                            }
                        }

                        if (!$error) {
                            $json_param['status'] = STATUS_TRUE;
                            $json_param['type'] = $type;
                            $json_param['txt'] = $successMessage . ($imageError ? ', with attachment upload error.' : '.');
                        } else {
                            $json_param['status'] = STATUS_FALSE;
                            $json_param['txt'] = $errorMessage;
                        }
                    } else {
                        $json_param['status'] = STATUS_FALSE;
                        $json_param['txt'] = __("A unique story title is required.");
                    }
                } else {
                    $json_param['status'] = STATUS_FALSE;
                    $json_param['txt'] = $errorMessage;
                }
            } else {
                $json_param['status'] = STATUS_FALSE;
                $json_param['txt'] = __(ERROR_MESSAGE);
            }
        } else {
            $json_param['status'] = STATUS_FALSE;
            $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
        }
        echo json_encode($json_param);
    }

    /**
     * Method delete
     *
     * @return void
     */
    public function delete(): void
    {
        if ($this->model_signup->hasPremiumPermission()) {

            $json_param = array();

            if (isset($_POST['id'])) {
                $storyDetail = $this->model_story->find_one_active(
                    array(
                        'where' => array(
                            'story_userid' => $this->userid,
                            'story_id' => $_POST['id']
                        )
                    )
                );

                if (!empty($storyDetail)) {
                    $updated = $this->model_story->update_model(
                        array(
                            'where' => array(
                                'story_userid' => $this->userid,
                                'story_id' => $_POST['id']
                            ),
                        ),
                        array(
                            'story_status' => STATUS_DELETE
                        )
                    );

                    if ($updated) {
                        $json_param['status'] = STATUS_TRUE;
                        $json_param['txt'] = __("Story deleted!");
                    } else {
                        $json_param['status'] = STATUS_FALSE;
                        $json_param['txt'] = __(ERROR_MESSAGE);
                    }
                } else {
                    $json_param['status'] = STATUS_FALSE;
                    $json_param['txt'] = __(ERROR_MESSAGE);
                }
            } else {
                $json_param['status'] = STATUS_FALSE;
                $json_param['txt'] = __(ERROR_MESSAGE);
            }
        } else {
            $json_param['status'] = STATUS_FALSE;
            $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
        }
        echo json_encode($json_param);
    }

    /**
     * Method post
     *
     * @param string $storySlug
     * @param string $edit
     *
     * @return void
     */
    public function post(string $storySlug = '', string $edit = ""): void
    {
        if ($this->model_signup->hasPremiumPermission()) {

            global $config;

            $data = array();

            $this->register_plugins("select2");

            if ($storySlug && $edit == 'edit') {
                $param = array();
                $param['where']['story_slug'] = $storySlug;
                $param['where']['story_userid'] = $this->userid;
                $data['story'] = $this->model_story->find_one($param);
                if (empty($data['story'])) {
                    $this->session->set_flashdata('error', __('Requested story doesn\'t exists!'));
                    redirect(l('dashboard/story/listing'));
                }
            }

            //
            $this->layout_data['title'] = 'Post Story | ' . $this->layout_data['title'];
            //
            $this->load_view("post", $data);
        } else {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL));
            redirect(l('dashboard'));
        }
    }

    /**
     * Method listing
     *
     * @param int $page
     * @param int $limit
     * @param int $userId
     *
     * @return void
     */
    public function listing(int $page = 1, int $limit = 6, int $userid = 0): void
    {
        if ($this->model_signup->hasPremiumPermission()) {

            global $config;

            $data = array();

            $data['page'] = $page;
            $paginationStart = ($page > 0) ? ($page - 1) * $limit : 0;

            $data['limit'] = $limit;

            // Prev + Next
            $data['prev'] = $page - 1;
            $data['next'] = $page + 1;

            $param = array();
            $count_param = array();

            $data['userid'] = $userid;

            if ($userid) {
                $count_param['where']['story_userid'] = $param['where']['story_userid'] = $userid;
            }
            // else {
                // $count_param['where']['story_userid'] = $param['where']['story_userid'] = $this->userid;
            // }

            $count_param['where_in']['story_status'] = $param['where_in']['story_status'] = [STATUS_ACTIVE, STATUS_INACTIVE];
            $param['order'] = 'story_id DESC';
            $data['offset'] = $param['offset'] = $paginationStart;
            $param['limit'] = $limit;

            $story = $this->model_story->find_all($param);

            $data['story'] = $story;

            $data['story_count'] = $allRecrods = count($this->model_story->find_all($count_param));

            // Calculate total pages
            $data['totalPages'] = ceil($allRecrods / $limit);

            //
            $this->layout_data['title'] = ($userid == 0 ? '' : 'My ') . 'Posted Stories | ' . $this->layout_data['title'];
            //
            $this->load_view("listing", $data);
        } else {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL));
            redirect(l('dashboard'));
        }
    }

    /**
     * Method detail
     *
     * @param string $storySlug
     *
     * @return void
     */
    public function detail(string $storySlug): void
    {
        if ($this->model_signup->hasPremiumPermission()) {

            $data = array();
            $data['story'] = array();

            $param = array();
            $param['where']['story_slug'] = $storySlug;
            // $param['where']['story_userid'] = $this->userid;
            $data['story'] = $this->model_story->find_one($param);

            if (empty($data['story'])) {
                $this->session->set_flashdata('error', __('Requested story doesn\'t exists or moved to a new page'));
                redirect(l('dashboard/story/listing'));
            }

            $story_id = $data['story']['story_id'];
            //
            $data['comment'] = $this->model_comment->find_all_active(
                array(
                    'order' => 'comment_id DESC',
                    'where' => array(
                        'comment_parent_id' => 0,
                        'comment_reference_id' => $story_id,
                        'comment_reference_type' => REFERENCE_TYPE_STORY
                    ),
                    'joins' => array(
                        0 => array(
                            'table' => 'signup',
                            'joint' => 'signup.signup_id = comment.comment_userid',
                            'type' => 'both'
                        ),
                        1 => array(
                            'table' => 'signup_info',
                            'joint' => 'signup_info.signup_info_signup_id = signup.signup_id',
                            'type' => 'both'
                        )
                    )
                )
            );

            // commenting purpose
            $data['type'] = REFERENCE_TYPE_STORY;
            $data['reference_id'] = $story_id;

            //
            $this->layout_data['title'] = $data['story']['story_title'] . ' | ' . $this->layout_data['title'];
            //
            $this->load_view("detail", $data);
        } else {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL));
            redirect(l('dashboard'));
        }
    }

    /**
     * Method deleteVideo
     *
     * @return void
     */
    function deleteVideo() : void {

        $json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = __(ERROR_MESSAGE);

        if ($this->model_signup->hasPremiumPermission()) {
            if (isset($_POST)) {
                $story_id = $_POST['id'];
                
                $param = array();
                $param['where']['story_id'] = $story_id;
                $storyDetail = $this->model_story->find_one($param);
                
                if (!empty($storyDetail)) {
                    $affect_param = array();
                    $param_name = isset($_POST['param']) && $_POST['param'] ? $_POST['param'] : '';
                    if($param_name) {
                        $affect_param[$param_name] = '';
                    } else {
                        $affect_param['story_video'] = '';
                    }

                    $affected = $this->model_story->update_by_pk($story_id, $affect_param);
                    if ($affected) {
                        $json_param['status'] = STATUS_TRUE;
                        $json_param['txt'] = __(SUCCESS_MESSAGE);
                    }
                } else {
                    $json_param['txt'] = __(ERROR_MESSAGE_RESOURCE_NOT_FOUND);
                }
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE);
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
        }
        echo json_encode($json_param);
    }

}
