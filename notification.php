<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$login_as_data_notification = array();

$login_as_data_notification['admin_notices']['login_as_b345'] = array(
    'class'=>'login_as_class',
    'random'=>true,
    'icon_path'=>plugins_url( '/images/icon-128x128.png' , __FILE__),
    'remind_period'=>69,
    'file_version'=>2.1,
    'development_mode'=>false,
    'url'=>array(
        array(
            'icon'=>'dashicons dashicons-external',
            'title'=>'Sure! I\'d love to!',
            'url'=>'https://wordpress.org/support/plugin/wp-content-copy-protector/reviews/?filter=5#new-post'
        )
    ),
    'already_review'=>true,
    'already_review_text'=>'I\'ve already left a review',
    'later_url'=>true,
    'later_url_text'=>'Will Rate Later',
    'Hide_Forever'=>true,
    'Hide_Forever_text'=>'Hide Forever',
    'data'=>array(
        array(
            'title'=>'titlerandom title title titlerandom title',
            'description'=>'description description description description description description'
        ),
        array(
            'title'=>'title1random title1 title1 title1random title1',
            'description'=>'description description description description description description'
        ),
        array(
            'title'=>'title2random title2 title2 title2random title2',
            'description'=>'description description description description description description'
        )
    )
);

$login_as_data_notification['admin_notices']['login_as_b346'] = array(
    'class'=>'login_as_class',
    'random'=>false,
    'icon_path'=>plugins_url( '/images/icon-128x128.png' , __FILE__),
    'remind_period'=>69,
    'file_version'=>2.1,
    'development_mode'=>false,
    'url'=>array(
        array(
            'icon'=>'dashicons dashicons-external',
            'title'=>'Sure! I\'d love to!',
            'url'=>'https://wordpress.org/support/plugin/wp-content-copy-protector/reviews/?filter=5#new-post'
        )
    ),
    'already_review'=>true,
    'already_review_text'=>'I\'ve already left a review',
    'later_url'=>true,
    'later_url_text'=>'Will Rate Later',
    'Hide_Forever'=>true,
    'Hide_Forever_text'=>'Hide Forever',
    'data'=>array(
        array(
            'title'=>'title12 title12 title12 title12 title12',
            'description'=>'description description description description description description'
        ),
        array(
            'title'=>'title11 title11 title11 title11 title11',
            'description'=>'description description description description description description'
        ),
        array(
            'title'=>'title21 title21 title21 title21 title21',
            'description'=>'description description description description description description'
        )
    )
);
$login_as_data_notification['admin_notices']['login_as_b348rt'] = array(
    'class'=>'login_as_class',
    'random'=>false,
    'icon_path'=>plugins_url( '/images/icon-128x128.png' , __FILE__),
    'remind_period'=>69,
    'file_version'=>2.1,
    'development_mode'=>false,
    'url'=>array(
        array(
            'icon'=>'dashicons dashicons-external',
            'title'=>'Sure!s I\'d love to!',
            'url'=>'https://wordpress.org/support/plugin/wp-content-copy-protector/reviews/?filter=5#new-post'
        )
    ),
    'already_review'=>true,
    'already_review_text'=>'I\'ve already left a review',
    'later_url'=>true,
    'later_url_text'=>'Will Rate Later',
    'Hide_Forever'=>true,
    'Hide_Forever_text'=>'Hide Forever',
    'data'=>array(
        array(
            'title'=>'title125 title125 title125 title125 title125',
            'description'=>'description description description description description description'
        ),
        array(
            'title'=>'title115 title115 title115 title115 title115',
            'description'=>'description description description description description description'
        ),
        array(
            'title'=>'title215 title215 title215 title215 title215',
            'description'=>'description description description description description description'
        )
    )
);


$login_as_data_notification['admin_notices']['login_as_b348'] = array(
    'class'=>'login_as_class',
    'random'=>true,
    'icon_path'=>plugins_url( '/images/icon-128x128.png' , __FILE__),
    'remind_period'=>69,
    'file_version'=>2.1,
    'development_mode'=>false,
    'url'=>array(
        array(
            'icon'=>'dashicons dashicons-external',
            'title'=>'Sure! I\'d love to!',
            'url'=>'https://wordpress.org/support/plugin/wp-content-copy-protector/reviews/?filter=5#new-post'
        )
    ),
    'already_review'=>true,
    'already_review_text'=>'I\'ve already left a review',
    'later_url'=>true,
    'later_url_text'=>'Will Rate Later',
    'Hide_Forever'=>true,
    'Hide_Forever_text'=>'Hide Forever',
    'data'=>array(
        array(
            'title'=>'random title125 title125 title125 random',
            'description'=>'description description description description description description'
        ),
        array(
            'title'=>'random title115 title115 title115 random',
            'description'=>'description description description description description description'
        ),
        array(
            'title'=>'random title215 title215 title215 random',
            'description'=>'description description description description description description'
        )
    )
);





if(!class_exists('login_as_notification')){
    class login_as_notification{
        public $notif_data = array();
        public function __construct($data){
            $this->notif_data = (array)$data;
            $this->_hooks();
        }

        private function _hooks() {
            add_action( 'admin_init', array( $this, 'free_review_notice' ) );
        }
        public function free_review_notice() {
            $data = $this->notif_data;

            foreach ($data as $key=>$vals) {

                $data_filters = array_filter($vals, function ($var) {
                    return ($var['random'] == false);
                });

                $data_random = array_filter($vals, function ($var) {
                    return ($var['random'] == true);
                });

                if(!empty($data_random)){
                    shuffle($data_random);
                    $firstKey = key($data_random); // Get the first key
                    $firstValues = $data_random[$firstKey];
                    $data_filters[$firstKey] = $firstValues;
                }



                foreach ($data_filters as $key_pref=>$val) {

                    $file_version = isset($val['file_version']) ? $val['file_version'] : 1;
                    $remind_me_later_period = isset($val['remind_period']) ? $val['remind_period'] : 1000;
                    $development_mode = isset($val['development_mode']) ? $val['development_mode'] : false;
                    $this->free_review_dismissal($key_pref); //will run if dismiss button clicked
                    $this->free_review_later($key_pref); //will run if remind me later button clicked

                    $remind_me_later_clicked_time 	= get_site_option( $key_pref.'_remind_me_later_clicked_time' );

                    $review_dismissal	= get_site_option( $key_pref.'_free_review_dismiss' );

                    if ($review_dismissal == 'yes' && !$development_mode){
                        continue;
                    }
                    if (!empty($remind_me_later_clicked_time) && !$development_mode){
                        if(time() < $remind_me_later_clicked_time+$remind_me_later_period){
                            continue;
                        }
                    }




                        wp_enqueue_style( 'free_review_stlye', plugins_url( '/assets/css/style-review.css', __FILE__ ), array(), $file_version );

                        add_action( $key , function () use ($val) {
                            $scheme      = ( wp_parse_url( $_SERVER['REQUEST_URI'], PHP_URL_QUERY ) ) ? '&' : '?';

                            $url         = $_SERVER['REQUEST_URI'] . $scheme . 'free_review_dismiss=yes';

                            $dismiss_url = wp_nonce_url( $url, 'free_review-nonce' );

                            $_later_link = $_SERVER['REQUEST_URI'] . $scheme . 'free_review_later=yes';

                            $later_url   = wp_nonce_url( $_later_link, 'free_review-nonce' );

                            $icon_path = $val['icon_path'];


                            $texts = array_rand($val['data']);
                            $text = $val['data'][$texts];

                            ?>

                            <div class="wccp_free_review-notice <?php esc_attr_e($val['class']);?>">
                                <div class="wccp_free_review-thumbnail">
                                    <img src="<?php esc_attr_e($icon_path); ?>" alt="">
                                </div>
                                <div class="wccp_free_review-text">

                                    <h3><?php _e( $text['title']) ?></h3>
                                    <p><?php _e( $text['description']) ?></p>
                                    <ul class="wccp_free_review-ul">
                                        <?php foreach ($val['url'] as $url_data) { ?>
                                            <li><a href="<?php esc_attr_e($url_data['url']); ?>" target="_blank"><span class="<?php echo $url_data['icon']; ?>"></span><?php _e($url_data['title'] ) ?></a></li><?php
                                        }
                                        if(isset($val['already_review']) && $val['already_review']){ ?>
                                            <li><a href="<?php esc_attr_e($dismiss_url); ?>"><span class="dashicons dashicons-smiley"></span><?php _e( $val['already_review_text']) ?></a></li>
                                        <?php }
                                        if(isset($val['later_url']) && $val['later_url']){ ?>
                                            <li><a href="<?php esc_attr_e($later_url); ?>"><span class="dashicons dashicons-calendar-alt"></span><?php _e( $val['later_url_text']) ?></a></li>
                                        <?php }
                                        if(isset($val['Hide_Forever']) && $val['Hide_Forever']){ ?>
                                            <li><a href="<?php esc_attr_e($dismiss_url); ?>"><span class="dashicons dashicons-dismiss"></span><?php _e( $val['Hide_Forever_text']) ?></a></li></ul>
                                        <?php } ?>
                                </div>
                            </div>
                            <?php
                        });





                }
            }
        }
        private function free_review_dismissal($key) {

            //Check if dismiss button is clicked and all security variables are OK
            if ( is_admin() &&
                current_user_can( 'manage_options' ) &&
                isset( $_GET['_wpnonce'] ) &&
                wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), 'free_review-nonce' ) &&
                isset( $_GET['free_review_dismiss'] ) )
            {
                // Set dismiss_forever option to (yes).
                add_site_option( $key.'_free_review_dismiss', 'yes' );
            }
        }
        private function free_review_later($key)
        {
            //Check if Remind_me_later button is clicked and all security variables are OK
            if ( is_admin() &&
                current_user_can( 'manage_options' ) &&
                isset( $_GET['_wpnonce'] ) &&
                wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), 'free_review-nonce' ) &&
                isset( $_GET['free_review_later'] ) )
            {
                // Reset Review_Later Time to current time.
                update_site_option( $key.'_remind_me_later_clicked_time', time() );
            }
        }




    }
}

new login_as_notification($login_as_data_notification);
