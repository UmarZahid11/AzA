<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * News
 */
class News extends MY_Controller
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
     * Method post
     *
     * @param string $newsSlug
     * @param string $edit
     *
     * @return void
     */
    public function post(string $newsSlug = '', string $edit = ""): void
    {
        if ($this->model_signup->hasPremiumPermission()) {

            global $config;

            $data = array();

            $this->register_plugins("select2");

            if ($newsSlug && $edit == 'edit') {
                $param = array();
                $param['where']['news_slug'] = $newsSlug;
                $param['where']['news_userid'] = $this->userid;
                $data['news'] = $this->model_news->find_one($param);

                if (empty($data['news'])) {
                    $this->session->set_flashdata('error', __('Requested news doesn\'t exists!'));
                    redirect(l('dashboard/news/listing'));
                }

                $tags = $this->model_tag->find_all_list(
                    array(
                        'where' => array(
                            'tag_reference_type' => REFERENCE_TYPE_NEWS,
                            'tag_reference_id' => $data['news']['news_id']
                        )
                    ),
                    'tag_name'
                );

                $data['tags'] = implode(',', $tags);
            }

            //
            $this->layout_data['title'] = 'Post News | ' . $this->layout_data['title'];
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
                $count_param['where']['news_userid'] = $param['where']['news_userid'] = $userid;
            }
            // else {
            // $count_param['where']['news_userid'] = $param['where']['news_userid'] = $this->userid;
            // }

            $count_param['where_in']['news_status'] = $param['where_in']['news_status'] = [STATUS_ACTIVE, STATUS_INACTIVE];
            $param['order'] = 'news_id DESC';
            $data['offset'] = $param['offset'] = $paginationStart;
            $param['limit'] = $limit;

            $news = $this->model_news->find_all($param);

            $data['news'] = $news;

            $data['news_count'] = $allRecrods = count($this->model_news->find_all($count_param));

            // Calculate total pages
            $data['totalPages'] = ceil($allRecrods / $limit);

            //
            $this->layout_data['title'] = ($userid == 0 ? '' : 'My ') . 'Posted News | ' . $this->layout_data['title'];
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
     * @param string $newsSlug
     *
     * @return void
     */
    public function detail(string $newsSlug): void
    {
        if ($this->model_signup->hasPremiumPermission()) {

            $data = array();
            $data['news'] = array();

            $param = array();
            $param['where']['news_slug'] = $newsSlug;
            // $param['where']['news_userid'] = $this->userid;
            $data['news'] = $this->model_news->find_one($param);

            if (empty($data['news'])) {
                $this->session->set_flashdata('error', __('Requested news doesn\'t exists or moved to a new page'));
                redirect(l('dashboard/news/listing'));
            }

            $news_id = $data['news']['news_id'];
            //
            $data['comment'] = $this->model_comment->find_all_active(
                array(
                    'order' => 'comment_id DESC',
                    'where' => array(
                        'comment_parent_id' => 0,
                        'comment_reference_id' => $news_id,
                        'comment_reference_type' => REFERENCE_TYPE_NEWS
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
            $data['type'] = REFERENCE_TYPE_NEWS;
            $data['reference_id'] = $news_id;

            $data['tags'] = $this->model_tag->find_all_active(
                array(
                    'where' => array(
                        'tag_reference_type' => REFERENCE_TYPE_NEWS,
                        'tag_reference_id' => $news_id
                    )
                )
            );

            //
            $this->layout_data['title'] = $data['news']['news_title'] . ' | ' . $this->layout_data['title'];
            //
            $this->load_view("detail", $data);
        } else {
            $this->session->set_flashdata('error', __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE_SWAL));
            redirect(l('dashboard'));
        }
    }

    /**
     * Method save
     *
     * @return void
     */
    public function save(): void
    {
        $json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = ERROR_MESSAGE;
        $videoError = FALSE;

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if ($this->model_signup->hasPremiumPermission()) {
                if (isset($_POST['news'])) {
                    $type = 'insert';
                    $error = false;
                    $imageError = false;
                    $errorMessage = __(ERROR_MESSAGE);
                    $successMessage = "Success";
                    $newsId = 0;
                    $newsImage = "";
                    $newsVideo = "";
                    $affectNews = $_POST['news'];

                    // check if news exists with the requested id
                    if (isset($_POST['news_id']) && intVal($_POST['news_id']) > 0) {
                        $param = array();
                        $param['where']['news_id'] = $_POST['news_id'];
                        $param['where']['news_userid'] = $this->userid;
                        $newsDetail = $this->model_news->find_one($param);
                        if (empty($newsDetail)) {
                            $error = true;
                            $errorMessage = __("Requested news doesn't exists");
                        } else {
                            $newsId = $newsDetail['news_id'];
                            $newsImage = $newsDetail['news_attachment'];
                            $newsVideo = $newsDetail['news_video'];
                        }
                    }

                    if (!$error) {
                        $param = array();
                        // exclude currently updating news for updation
                        if ($newsId) {
                            $param['where']['news_id !='] = $_POST['news_id'];
                        }
                        $param['where']['news_slug'] = $affectNews['news_slug'];
                        $newsDetail = $this->model_news->find_one($param);

                        if (empty($newsDetail)) {

                            // Get upload path
                            $upload_path = 'assets/uploads/news/';
                            $affectNews['news_attachment_path'] = $upload_path;

                            if (($_FILES['file']['error'] == 0)) {
                                if($_FILES['file']['size'] < MAX_FILE_SIZE) {
                                    // Get temp file
                                    $tmp = $_FILES['file']['tmp_name'];
                                    // Generate file name
                                    $name = mt_rand() . $_FILES['file']['name'];

                                    // Set data
                                    $affectNews['news_attachment'] = $name;
    
                                    // Remove old file
                                    if ($newsImage) {
                                        unlink('assets/uploads/news/' . basename($newsImage));
                                    }
    
                                    // Upload new file
                                    if (!move_uploaded_file($tmp, $upload_path . $name)) {
                                        $imageError = true;
                                    }
                                } else {
                                    $imageError = true;
                                }
                            }

                            if (($_FILES['news_video']['error'] == 0)) {
                                
                                if($_FILES['news_video']['size'] < MAX_FILE_SIZE) {
                                    // Get temp file
                                    $tmp = $_FILES['news_video']['tmp_name'];
                                    // Generate file name
                                    $name = mt_rand() . $_FILES['news_video']['name'];
    
                                    // Set data
                                    $affectNews['news_video'] = $name;
    
                                    // Remove old file
                                    if ($newsVideo) {
                                        unlink('assets/uploads/news/' . basename($newsVideo));
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
                            if ($newsId) {
                                $inserted = $this->model_news->update_by_pk($newsId, $affectNews);

                                $type = 'update';
                                $successMessage = __('Changes have been saved.');

                                // if error
                                if (!$inserted) {
                                    $error = true;
                                    $errorMessage = __("Nothing to update!");
                                }
                            } else {
                                $newsId = $inserted = $this->model_news->insert_record($affectNews);

                                // if error
                                if (!$inserted) {
                                    $error = true;
                                    $errorMessage = __(ERROR_MESSAGE);
                                } else {
                                    $type = 'insert';
                                    $successMessage = __('News has been saved');
                                }
                            }

                            $previous_tags = $this->model_tag->find_all_list(
                                array(
                                    'where' => array(
                                        'tag_reference_id' => $newsId,
                                        'tag_reference_type' => REFERENCE_TYPE_NEWS
                                    )
                                ),
                                'tag_name'
                            );
                            $previous_tags = implode(",", $previous_tags);
                            if($previous_tags != $_POST['tag']) {
                                $error = FALSE;
                            }

                            if (!$error) {

                                if (isset($_POST['tag'])) {

                                    $this->model_tag->delete_record_custon(
                                        array(
                                            'where' => array(
                                                'tag_reference_id' => $newsId,
                                                'tag_reference_type' => REFERENCE_TYPE_NEWS
                                            )
                                        )
                                    );
                                    $tags = explode(",", $_POST['tag']);
                                    foreach ($tags as $tag) {
                                        $this->model_tag->insert_record(
                                            array(
                                                'tag_reference_id' => $newsId,
                                                'tag_reference_type' => REFERENCE_TYPE_NEWS,
                                                'tag_name' => $tag
                                            )
                                        );
                                    }
                                }

                                $json_param['status'] = STATUS_TRUE;
                                $json_param['type'] = $type;
                                $json_param['txt'] = $successMessage . (($imageError || $videoError) ? ', with attachment upload error.' : '.');
                            } else {
                                $json_param['txt'] = $errorMessage;
                            }
                        } else {
                            $json_param['txt'] = __("A unique title is required.");
                        }
                    } else {
                        $json_param['txt'] = $errorMessage;
                    }
                } else {
                    $json_param['txt'] = __(ERROR_MESSAGE);
                }
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
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
        $json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = ERROR_MESSAGE;

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if ($this->model_signup->hasPremiumPermission()) {
                if (isset($_POST['id'])) {
                    $newsDetails = $this->model_news->find_one_active(
                        array(
                            'where' => array(
                                'news_userid' => $this->userid,
                                'news_id' => $_POST['id']
                            )
                        )
                    );

                    if (!empty($newsDetails)) {
                        $updated = $this->model_news->update_model(
                            array(
                                'where' => array(
                                    'news_userid' => $this->userid,
                                    'news_id' => $_POST['id']
                                ),
                            ),
                            array(
                                'news_status' => STATUS_DELETE
                            )
                        );

                        if ($updated) {
                            $json_param['status'] = STATUS_TRUE;
                            $json_param['txt'] = __("News has been deleted!");
                        } else {
                            $json_param['txt'] = __(ERROR_MESSAGE);
                        }
                    } else {
                        $json_param['txt'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
                    }
                } else {
                    $json_param['txt'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
                }
            } else {
                $json_param['txt'] = __(ERROR_MESSAGE_INSUFFICIENT_PRIVILEGE);
            }
        } else {
            $json_param['txt'] = __(ERROR_MESSAGE_LINK_EXPIRED);
        }
        echo json_encode($json_param);
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
                $news_id = $_POST['id'];
                
                $param = array();
                $param['where']['news_id'] = $news_id;
                $newsDetail = $this->model_news->find_one($param);
                
                if (!empty($newsDetail)) {
                    $affect_param = array();
                    $param_name = isset($_POST['param']) && $_POST['param'] ? $_POST['param'] : '';
                    if($param_name) {
                        $affect_param[$param_name] = '';
                    } else {
                        $affect_param['news_video'] = '';
                    }

                    $affected = $this->model_news->update_by_pk($news_id, $affect_param);
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
