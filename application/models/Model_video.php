<?
class Model_video extends MY_Model {
    /**
     * TKD video MODEL
     *
     * @package     Model_video Model
     * 
     * @version     2.0
     */

    protected $_table    = 'video';
    protected $_field_prefix    = 'video_';
    protected $_pk    = 'video_id';
    protected $_status_field    = 'video_status';
    public $relations = array();
    public $pagination_params = array();
    public $dt_params = array();
    public $_per_page    = 20;
    
    function __construct()
    {
        // Call the Model constructor
//        $this->pagination_params['fields'] = "{pre}video.video_id,{pre}video.video_type,{pre}video.video_title,
        $this->pagination_params['fields'] = "video_id,video_title,video_status";
                                                
        /*$this->pagination_params['joins'][] = array(
                                                    "table"=>"video AS parent_cat" ,
                                                    "joint"=>"video.video_parent_id = parent_cat.video_id",
                                                    "type"=>"left" 
                                                );*/

        
        //$this->pagination_params['where']['video.video_parent_id >'] = 0 ;
        parent::__construct();

    }

    // Check slug exists or not
    public function find_by_slug($slug)
    {
        // Set params
        $param['fields'] = "video_id,video_slug,video_title,video_detail,video_parent_id";
        $param['where']['video_slug'] = $slug;
        // Query
        $result = $this->find_one_active($param);

        // Return result;
        return $result;
    }
    // Check slug exists or not
    public function find_by_slug_all($slug)
    {
        // Set params
        $param['where']['video_slug'] = $slug;
        // Query
        $result = $this->find_one_active($param);

        // Return result;
        return $result;
    }

    // Get total post active
    public function get_total_count($cat_id)
    {
        // Set params
        $params['where']['product_video_id'] = $cat_id;

        return $this->model_product->find_count_active($params);
    }

    // Get pagination data
    public function get_pagination_data($limit = '', $offset = '', $product_cat_id=0)
    {
        $prefix = $this->db->dbprefix;
        // Set params
        /*$param['fields'] = "product_id,product_name,product_slug,product_image, product_image_thumb,,product_image_path,product_description,product_image,product_image_thumb,product_image_path,product_createdon,
        (SELECT COUNT(comment_post_id) FROM ".$prefix."comment WHERE comment_post_id = product_id and comment_status=1) AS total_comments, product_video_title";*/
        $param['fields'] = "product_id,product_video_id,product_name,product_slug,product_price,product_overview,
        product_image,,product_image_thumb,product_image_path,product_createdon,video_title";
        // LEFT JOIN
        // LEFT JOIN
        $param['joins'][] = array(
            'table' => 'video',
            'joint' => 'video.video_id = product.product_video_id',
            'type' => 'right'
        );
        $param['where']['product.product_video_id'] = $product_cat_id;
        $param['order'] = 'product_id DESC';
        $param['limit'] = $limit;
        $param['offset'] = $offset;
        // Query data
        $data = $this->model_product->find_all_active($param);

        // Return result
        return $data;
    }

    public function get_categories()
    {
        $para['where']['video_id > '] = 2;
        $para['order'] = "video_title ASC";
        $categories = $this->model_video->find_all_list_active($para,"video_title");

        return $categories;
    }

    public function get_menu_categories($id=0)
    {
        $params['fields'] = 'video_id,video_parent_id,video_title,video_image,video_image_thumb,video_image_path,video_slug';
        $params['order'] = 'video_parent_id';
        $data = $this->find_all_active($params);
        
        $result = array();
        foreach ($data as $key => $value) 
        {
            $result[ $value['video_id'] ] = $value;
        }
        foreach ($result as $key => $value) 
        {
            $children[ $value['video_parent_id'] ][$key] = $value;
        }
        $menu_categories = (recursive_array($result , $children));
        return $menu_categories[1] ;
    }

    // Get Parent hirarechy for Categories
    public function get_ancestory($value , $key = "video_id" , $fields = "t.*" )
    {
        $result = array();
        $value = urldecode($value);
        if($key && $value && $this->get_fields($key) )
        {
            $query = "SELECT @pv:=t.video_parent_id as video_parent_id, $fields
                        from  (SELECT * FROM pg_video ORDER BY video_id DESC) t 
                        JOIN
                        (SELECT @pv:= (SELECT video_id FROM pg_video WHERE $key = '$value' ))tmp
                        WHERE t.video_id=@pv AND t.video_id > 1 AND t.video_status = ".STATUS_ACTIVE;
            $result = $this->db->query($query)->result_array();
            return $result;
        }
    }

    // Get All Children Under A Cateogry
    public function get_children_by_parent_id($parent_id )
    {
        $result = array();
        $params['where']['video_parent_id'] = intval($parent_id);
        return $this->find_all_active($params);
    }

    /*
    * table             Table Name
    * Name              FIeld Name
    * label             Field Label / Textual Representation in form and DT headings
    * type              Field type : hidden, text, textarea, editor, etc etc. 
    *                                 Implementation in form_generator.php
    * type_dt           Type used by prepare_datatables method in controller to prepare DT value
    *                                 If left blank, prepare_datatable Will opt to use 'type'
    * type_filter_dt    Used by DT FILTER PREPRATION IN datatables.php
    * attributes        HTML Field Attributes
    * js_rules          Rules to be aplied in JS (form validation)
    * rules             Server side Validation. Supports CI Native rules
    * 
    * list_data         For dropdown etc, data in key-value pair that will populate dropdown 
    *                   -----USED IN ADMIN_CONTROLLER AND admin's database.php
    * list_data_key     For dropdown etc, if you want to define list_data in CONTROLLER (public _list_data[$key]) list_data_key is the $key which identifies it
    *                   -----Incase list_data_key is not defined, it will look for field_name as a $key
    *                   -----USED IN ADMIN_CONTROLLER AND admin's database.php
    */
    public function get_fields( $specific_field = "" )
    {

        $segment = array(2);
        $segment_id = $this->uri->segment(4);
        // Use when add new image
        $is_required_image = (($segment_id != null) && intval($segment_id)) ? '' : ''; // required
// debug($is_required_image,1);

        $fields = array(
        
              'video_id' => array(
                     'table'   => $this->_table,
                     'name'   => 'video_id',
                     'label'   => 'id #',
                     'type'   => 'hidden',
                     'type_dt'   => 'text',
                     'attributes'   => array(),
                     'dt_attributes'   => array("width"=>"5%"),
                     'js_rules'   => '',
                     'rules'   => 'trim'
                ),

               /*'video_type' => array(
                      'table'   => $this->_table,
                      'name'   => 'video_type',
                      'label'   => 'Category Type',
                      'type'   => 'text',
                      'type_dt'   => 'text',
                      'attributes'   => array(),
                      'dt_attributes'   => array("width"=>"5%"),
                      'js_rules'   => '',
                      'rules'   => 'trim'
                 ),*/

            /*'video_type' => array(
                   'table'   => $this->_table,
                   'name'   => 'video_type',
                   'label'   => 'Level',
                   'type'   => 'dropdown',
                   'list_data'    => array("1"=>"Parent","2"=>"Level 1","3"=>"Level 2","4"=>"Level 3") ,
                   'attributes'   => array(),
                   'js_rules'   => 'required',
                   'rules'   => 'required|trim',
                ),

              'video_parent_id' => array(
                     'table'   => $this->_table,
                     'name'   => 'video_parent_id',
                     'label'   => 'Category ID',
                     'type'   => 'dropdown',
                     'type_dt'   => 'text',
                     'type_filter_dt'   => 'dropdown',
                     'attributes'   => array(),
                     'dt_attributes'   => array("width"=>"10%"),
                     'js_rules'   => 'required',
                     'rules'   => 'required|trim'
            ),*/

              'video_title' => array(
                     'table'   => $this->_table,
                     'name'   => 'video_title',
                     'label'   => 'Title',
                     'type'   => 'text',
                     //'attributes'   => array("additional"=>'slugify="#'.$this->_table.'-'.$this->_field_prefix.'slug"'),
                     'js_rules'   => 'required',
                     'rules'   => 'required|trim|htmlentities'
                  ),             
			'video_short_description' => array(                'table'   => $this->_table,                'name'   => 'video_short_description',                'label'   => 'Short description',                'type'   => 'textarea',                'type_dt'   => 'text',                'attributes'   => array(),                'dt_attributes'   => array("width" => "5%"),                'js_rules'   => '',                'rules'   => 'trim'            ),						'video_description' => array(                'table'   => $this->_table,                'name'   => 'video_description',                'label'   => 'Description',                'type'   => 'editor',                'type_dt'   => 'text',                'attributes'   => array(),                'dt_attributes'   => array("width" => "5%"),                'js_rules'   => '',                'rules'   => 'trim'            ),			
             /* 'video_slug'  => array(
                  'table'   => $this->_table,
                  'name'   => 'video_slug',
                  'label'   => 'Title',
                  'type'   => 'hidden',
                  'attributes'   => array(),
                  'js_rules'   => array("is_slug" => array() ),
                  'rules'   => 'required|htmlentities|is_unique['.$this->_table.'.'.$this->_field_prefix.'slug]|callback_is_slug|strtolower'
              ),*/

              // 'video_url'  => array(
              //     'table'   => $this->_table,
              //     'name'   => 'video_url',
              //     'label'   => 'Youtube Embed URL',
              //     'type'   => 'text',
              //     'attributes'   => array(),
              //     'js_rules'   => 'required',
              //     'rules'   => 'required'
              // ),

            'video_banner_image' => array(
                'table' => $this->_table,
                'name' => 'video_banner_image',
                'label' => 'Video Cover Image',
                'name_path' => 'video_image_path',
                'upload_config' => 'site_upload_video',
                'type' => 'fileupload',
                'type_dt' => 'image',
                'randomize' => true,
                'preview' => 'true',
                'attributes'   => array(
                    'image_size_recommended'=>'1263px × 517px',
                    'allow_ext'=>'png|jpeg|jpg',
                ),
                'dt_attributes' => array("width" => "10%"),
                'rules' => '',
                'js_rules'=> $is_required_image
            ),

            // 'video_image' => array(
                // 'table' => $this->_table,
                // 'name' => 'video_image',
                // 'label' => 'Video',
                // 'name_path' => 'video_image_path',
                // 'upload_config' => 'site_upload_video',
                // 'type' => 'videoupload',
                // 'type_dt' => 'image',
                // 'randomize' => true,
                // 'preview' => 'true',
                // 'attributes'   => array(
                    // 'image_size_recommended'=>'1263px × 517px',
                    // 'allow_ext'=>'mp4',
                // ),
                // 'dt_attributes' => array("width" => "10%"),
                // 'rules' => '',
                // 'js_rules'=> $is_required_image
            // ),

              
              'video_status' => array(
                     'table'   => $this->_table,
                     'name'   => 'video_status',
                     'label'   => 'Status?',
                     'type'   => 'switch',
                     'type_dt'   => 'dropdown',
                     'type_filter_dt' => 'dropdown',
                     'list_data_key' => "video_status" ,
                      'list_data' => array(
                          0 => "<span class='label label-danger'>Inactive</span>" ,
                          1 =>  "<span class='label label-primary'>Active</span>"
                      ) ,
                     'default'   => '1',
                     'attributes'   => array(),
                     'dt_attributes'   => array("width"=>"7%"),
                     'rules'   => 'trim'
                  ),
              
            );
        
        if($specific_field)
            return $fields[ $specific_field ];
        else
            return $fields;
    }

}
?>