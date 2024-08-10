<?php

class TTG_GF_KLAVIYO_LIST
{
    private $klaviyoApi;
    public function __construct()
    {
        $this->klaviyoApi = gf_klaviyo_api_feed();
        add_filter('gform_addon_navigation', array($this, 'create_menu'));
        add_action('admin_enqueue_scripts', array($this, 'load_scripts'));

        add_action('wp_ajax_create_list', array($this, 'create_list'));
    }

    public function create_list()
    {
        $data = '';
        if (!empty($_GET['list-name'])) {
            $data  = $this->klaviyoApi->getKlaviyoClient()->create_list($_GET['list-name']);
        }

        echo json_encode([
            'data' => $data
        ]);
        wp_die();
    }

    public function load_scripts()
    {
        $page = !empty($_GET['page']) ? $_GET['page'] : '';
        if ($page != 'ttg-gf-klaviyo-list') {
            return;
        }
        wp_enqueue_style('app', plugins_url('assets/app.css', __FILE__));
        wp_enqueue_script('app', plugins_url('assets/app.js', __FILE__), array('jquery'), false, true);
        wp_localize_script(
            'app',
            'ajaxObject',
            array('ajaxUrl' => admin_url('admin-ajax.php'))
        );
    }

    public function create_menu($menus)
    {
        $menus[] = array('name' => 'ttg-gf-klaviyo-list', 'label' => __('Klaviyo List'), 'callback' =>  array($this,  'page'));
        return $menus;
    }
    public function page()
    {
        $this->renderList();
    }

    public function renderList()
    {
        if (!$this->klaviyoApi->isKeyValid()) {
            echo '<div class="wrap">' . $this->klaviyoApi->configure_addon_message() . '</div>';
            return '';
        }
        $list = $this->klaviyoApi->getKlaviyoClient()->get_lists();
?>
        <div class="wrap">
            <h2 class="wp-heading-inline">Klaviyo List <a id="add-klaviyo-list" href="#" class="page-title-action">Add New</a></h2>
            <table id="table-klaviyo-list" class="wp-list-table widefat fixed striped table-view-list posts">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($list)) {
                        foreach ($list as $key => $value) {

                    ?>
                            <tr>
                                <td><?php echo $value['list_id'] ?></td>
                                <td><?php echo $value['list_name'] ?></td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>

            </table>
            <div id="klaviyo-list-form-wrapper">
                <form id="klaviyo-list-form" class="form-wrap" action="">
                    <h2>Create List</h2>
                    <div class="form-field form-required term-name-wrap">
                        <label for="name">List Name <span>*</span></label>
                        <input name="list-name" type="text" value="" aria-required="true">
                    </div>
                    <input type="hidden" name="action" value="create_list" />
                    <input type="submit" name="save" class="button button-primary" value="Submit">
                    <input type="button" id="close-klaviyo-list-form" class="button button-secondary" value="Cancel">
                </form>
            </div>
        </div>
<?php
    }
}

new TTG_GF_KLAVIYO_LIST();
