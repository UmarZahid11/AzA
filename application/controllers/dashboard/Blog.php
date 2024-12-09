<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Blog
 */
class Blog extends MY_Controller
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
     * @param string $blogSlug
     * @param string $edit
     *
     * @return void
     */
    public function post(string $blogSlug = '', string $edit = ""): void
    {
        if ($this->model_signup->hasPremiumPermission()) {

            global $config;

            $data = array();

            $this->register_plugins("select2");

            if ($blogSlug && $edit == 'edit') {
                $param = array();
                $param['where']['blog_slug'] = $blogSlug;
                $param['where']['blog_userid'] = $this->userid;
                $data['blog'] = $this->model_blog->find_one($param);
                if (empty($data['blog'])) {
                    $this->session->set_flashdata('error', __('Requested blog doesn\'t exists!'));
                    redirect(l('dashboard/blog/listing'));
                }
            }

            //
            $this->layout_data['title'] = 'Post Blog | ' . $this->layout_data['title'];
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
                $count_param['where']['blog_userid'] = $param['where']['blog_userid'] = $userid;
            }
            // else {
                // $count_param['where']['blog_userid'] = $param['where']['blog_userid'] = $this->userid;
            // }

            $count_param['where_in']['blog_status'] = $param['where_in']['blog_status'] = [STATUS_ACTIVE, STATUS_INACTIVE];
            $param['order'] = 'blog_id DESC';
            $data['offset'] = $param['offset'] = $paginationStart;
            $param['limit'] = $limit;
            
            if(isset($_GET['search']) && $_GET['search']) {
                $data['search'] = $_GET['search'];
                $count_param['where_like'][] = $param['where_like'][] = array(
                    'column' => 'blog_title',
                    'value' => $data['search'],
                    'type' => 'both',
                );
            }
            
            $blog = $this->model_blog->find_all($param);

            $data['blog'] = $blog;

            $data['blog_count'] = $allRecrods = count($this->model_blog->find_all($count_param));

            // Calculate total pages
            $data['totalPages'] = ceil($allRecrods / $limit);

            //
            $this->layout_data['title'] = ($userid == 0 ? '' : 'My ') . 'Posted Blogs | ' . $this->layout_data['title'];
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
     * @param string $blogSlug
     *
     * @return void
     */
    public function detail(string $blogSlug = ''): void
    {
        if ($this->model_signup->hasPremiumPermission()) {

            if(!$blogSlug) {
                error_404();
            }

            $data = array();
            $data['blog'] = array();

            $param = array();
            $param['where']['blog_slug'] = $blogSlug;
            // $param['where']['blog_userid'] = $this->userid;
            $data['blog'] = $this->model_blog->find_one($param);

            if (empty($data['blog'])) {
                $this->session->set_flashdata('error', __('Requested blog doesn\'t exists or moved to a new page'));
                redirect(l('dashboard/blog/listing'));
            }

            $blog_id = $data['blog']['blog_id'];
            //
            $data['comment'] = $this->model_comment->find_all_active(
                array(
                    'order' => 'comment_id DESC',
                    'where' => array(
                        'comment_parent_id' => 0,
                        'comment_reference_id' => $blog_id,
                        'comment_reference_type' => REFERENCE_TYPE_BLOG
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
            $data['type'] = REFERENCE_TYPE_BLOG;
            $data['reference_id'] = $blog_id;

            //
            $this->layout_data['title'] = $data['blog']['blog_title'] . ' | ' . $this->layout_data['title'];
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

        if (isset($_POST['_token']) && $this->verify_csrf_token($_POST['_token'])) {
            if ($this->model_signup->hasPremiumPermission()) {
                if (isset($_POST['blog'])) {
                    $type = 'insert';
                    $error = false;
                    $imageError = false;
                    $videoError = false;
                    $errorMessage = __(ERROR_MESSAGE);
                    $successMessage = "Success";
                    $blogId = 0;
                    $blogImage = "";
                    $blogVideo = "";
                    $affectBlog = $_POST['blog'];

                    // check if blog exists with the requested id
                    if (isset($_POST['blog_id']) && intVal($_POST['blog_id']) > 0) {

                        $param = array();
                        $param['where']['blog_id'] = $_POST['blog_id'];
                        $param['where']['blog_userid'] = $this->userid;
                        $blogDetail = $this->model_blog->find_one($param);

                        if (empty($blogDetail)) {
                            $error = true;
                            $errorMessage = __("Requested blog doesn't exists");
                        } else {
                            $blogId = $blogDetail['blog_id'];
                            $blogImage = $blogDetail['blog_image'];
                            $blogVideo = $blogDetail['blog_video'];
                        }
                    }

                    if (!$error) {
                        $param = array();
                        // exclude currently updating blog for updation
                        if ($blogId) {
                            $param['where']['blog_id !='] = $_POST['blog_id'];
                        }
                        $param['where']['blog_slug'] = $affectBlog['blog_slug'];
                        $blogDetail = $this->model_blog->find_one($param);

                        if (empty($blogDetail)) {

                            // Get upload path
                            $upload_path = 'assets/uploads/blog/';
                            $affectBlog['blog_image_path'] = $upload_path;

                            if (($_FILES['file']['error'] == 0)) {
                                if($_FILES['file']['size'] < MAX_FILE_SIZE) {
                                    // Get temp file
                                    $tmp = $_FILES['file']['tmp_name'];
                                    // Generate file name
                                    $name = mt_rand() . $_FILES['file']['name'];
    
                                    // Set data
                                    $affectBlog['blog_image'] = $name;
    
                                    // Remove old file
                                    if ($blogImage) {
                                        unlink('assets/uploads/blog/' . basename($blogImage));
                                    }
    
                                    // Upload new file
                                    if (!move_uploaded_file($tmp, $upload_path . $name)) {
                                        $imageError = true;
                                    }
                                } else {
                                    $imageError = true;
                                }
                            }

                            if (($_FILES['blog_video']['error'] == 0)) {
                                
                                if($_FILES['blog_video']['size'] < MAX_FILE_SIZE) {
                                    // Get temp file
                                    $tmp = $_FILES['blog_video']['tmp_name'];
                                    // Generate file name
                                    $name = mt_rand() . $_FILES['blog_video']['name'];
    
                                    // Set data
                                    $affectBlog['blog_video'] = $name;
    
                                    // Remove old file
                                    if ($blogVideo) {
                                        unlink('assets/uploads/blog/' . basename($blogVideo));
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
                            if ($blogId) {
                                $inserted = $this->model_blog->update_by_pk($blogId, $affectBlog);

                                $type = 'update';
                                $successMessage = __('Changes have been saved!');

                                // if error
                                if (!$inserted) {
                                    $error = true;
                                    $errorMessage = __("Nothing to update!");
                                }
                            } else {
                                $blogId = $inserted = $this->model_blog->insert_record($affectBlog);

                                $type = 'insert';
                                $successMessage = __('Blog has been saved.');

                                // if error
                                if (!$inserted) {
                                    $error = true;
                                    $errorMessage = __("Nothing to update!");
                                }
                            }

                            if (!$error) {

                                if (isset($_POST['tag'])) {
                                    $this->model_tag->delete_record_custon(
                                        array(
                                            'where' => array(
                                                'tag_reference_id' => $blogId,
                                                'tag_reference_type' => REFERENCE_TYPE_BLOG
                                            )
                                        )
                                    );
                                    $tags = explode(",", $_POST['tag']);
                                    foreach ($tags as $tag) {
                                        $this->model_tag->insert_record(
                                            array(
                                                'tag_reference_id' => $blogId,
                                                'tag_reference_type' => REFERENCE_TYPE_BLOG,
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
     * Method delete - blog
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
                    $blogDetails = $this->model_blog->find_one_active(
                        array(
                            'where' => array(
                                'blog_userid' => $this->userid,
                                'blog_id' => $_POST['id']
                            )
                        )
                    );

                    if (!empty($blogDetails)) {
                        $updated = $this->model_blog->update_model(
                            array(
                                'where' => array(
                                    'blog_userid' => $this->userid,
                                    'blog_id' => $_POST['id']
                                ),
                            ),
                            array(
                                'blog_status' => STATUS_DELETE
                            )
                        );

                        if ($updated) {
                            $json_param['status'] = STATUS_TRUE;
                            $json_param['txt'] = __("Blog has been deleted!");
                        } else {
                            $json_param['txt'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
                        }
                    } else {
                        $json_param['txt'] = __(ERROR_MESSAGE_INVALID_PAYLOAD);
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
     * Method deleteVideo
     *
     * @return void
     */
    function deleteVideo() : void {

        $json_param['status'] = STATUS_FALSE;
        $json_param['txt'] = __(ERROR_MESSAGE);

        if ($this->model_signup->hasPremiumPermission()) {
            if (isset($_POST)) {
                $blog_id = $_POST['id'];
                
                $param = array();
                $param['where']['blog_id'] = $blog_id;
                $blogDetail = $this->model_blog->find_one($param);
                
                if (!empty($blogDetail)) {
                    $affect_param = array();
                    $param_name = isset($_POST['param']) && $_POST['param'] ? $_POST['param'] : '';
                    if($param_name) {
                        $affect_param[$param_name] = '';
                    } else {
                        $affect_param['blog_video'] = '';
                    }

                    $affected = $this->model_blog->update_by_pk($blog_id, $affect_param);
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
