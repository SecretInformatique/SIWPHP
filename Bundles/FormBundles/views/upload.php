<?php


wp_enqueue_media();

// Register, localize and enqueue our custom JS.
wp_register_script( 'upload-meta-box', $this->app()->path('theme').'/siwphp/Bundles/FormBundles/js/upload.js', array( 'jquery' ), '1.0.0', true );
wp_enqueue_script( 'upload-meta-box' );

global $post;

// Get WordPress' media upload URL

$id_media = $options['value'];

$upload_link = esc_url( get_upload_iframe_src( 'image', $id_media  ) );

// Get the image src
$your_img_src = wp_get_attachment_image_src( $id_media, 'full' );
// For convenience, see if the array is valid
$you_have_img = is_array( $your_img_src );
?>



<div class="meta-box-item-title">
	<h4><?php if (isset($label)) echo $label; ?></h4>
</div>
<div class="meta-box-item-content">
    <!-- Your image container, which can be manipulated with js -->
    <div class="custom-img-container">
        <?php if ( $you_have_img ) : ?>
            <img src="<?php echo $your_img_src[0] ?>" alt="" style="max-width:100%;" />
        <?php endif; ?>
    </div>

    <!-- Your add & remove image links -->
    <p class="hide-if-no-js">
        <a class="upload-custom-img <?php if ( $you_have_img  ) { echo 'hidden'; } ?>"
           href="<?php echo $upload_link ?>">
            <?php _e('Set custom image') ?>
        </a>
        <a class="delete-custom-img <?php if ( ! $you_have_img  ) { echo 'hidden'; } ?>"
          href="#">
            <?php _e('Remove this image') ?>
        </a>
    </p>
    <!-- A hidden input to set and post the chosen image id -->
    <input class="custom-img-id" name="<?php if (isset($name)) echo $name; ?>" type="hidden" value="<?php echo $options['value']; ?>" />

    <script type="text/javascript">
        var id_meta_box = '#<?php if (isset($id_meta_box)) echo $id_meta_box; ?>';
    </script>
</div>
