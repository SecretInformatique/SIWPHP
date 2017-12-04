<?php
    $options_html = '';

    if (isset($options) && is_array($options))
    {
        foreach ($options as $key => $value)
        {
            if (is_string($key) && (is_string($value) || is_numeric($value)))
                $options_html .= ' '.$key.'="'.$value.'"';
        }
    }
?>

<div class="meta-box-item-title">
    <h4><?php if (isset($label)) echo $label; ?></h4>
</div>
<div class="meta-box-item-content">
    <input name="<?php if (isset($name)) echo $name; ?>" id="<?php if (isset($name)) echo $name; ?>" type="text" <?php echo $options_html; ?> style="width:100%;"/>
</div>
