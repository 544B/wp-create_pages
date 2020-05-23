<?php
require_once( dirname( __FILE__ ) . '/wp-load.php' );
$json = file_get_contents( dirname( __FILE__ ) . '/sitemap.json');
if ($json === false) {
    throw new \RuntimeException('file not found.');
}
$data = json_decode($json, true);

function message($title, $text) {
    echo '[' .$title. ']: ' .$text . PHP_EOL;
}
function exists_page($slug) {
    return !(count(get_posts("post_type=page&name=${slug}")) > 0);
}
function create_template($slug, $title) {
    $file = get_template_directory() . '/page-' . $slug . '.php';
    if(!file_exists($file)) {
        if(touch($file)) {
            $contents = '<?php /* Template Name: ' . $title . ' */ ?>' . PHP_EOL . $title;
            file_put_contents($file, $contents);
            message('CREATE_TEMPLATE', 'page-' . $slug . '.php');
        }
    }
}
function create_page($slug, $title, $parent = null) {
    if(!exists_page($slug)) {
        message('SKIP_PAGE', $title);
    } else {
        $template = !empty($parent) ? $parent['slug'] . '-' . $slug : $slug;
        $post_value = array(
            'post_author' => 1,
            'post_type' => 'page',
            'post_content' => '',
            'post_status' => 'publish',
            'post_title' =>$title,
            'post_name' => $slug
        );
        if(!empty($parent)) $post_value['post_parent'] = $parent['id'];
        $new_page_id = wp_insert_post($post_value);
        if ( $new_page_id && ! is_wp_error( $new_page_id ) ){
            update_post_meta( $new_page_id, '_wp_page_template', 'page-' . $template . '.php');
        }
        message('CREATE_PAGE', $title);
    }
}

echo '<pre>' . PHP_EOL;
foreach($data['page'] as $page) {
    create_template($page['slug'], $page['title']);
    create_page($page['slug'], $page['title']);

    if(!empty($page['children'])) {
        $parent = array(
            'id' => get_page_by_path($page['slug'])->ID,
            'slug' => $page['slug']
        );
        foreach($page['children'] as $child) {
            create_template($page['slug'].'-'.$child['slug'], $child['title']);
            create_page($child['slug'], $child['title'], $parent);
        }
    }
}
echo '</pre>' . PHP_EOL;
