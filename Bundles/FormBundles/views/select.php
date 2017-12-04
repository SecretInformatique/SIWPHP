<?php
    $options_html = '';
    $options_option = '';

    if (isset($options) && is_array($options))
    {
        if (isset($options['option']) && !empty($options['option']))
        {
            foreach ($options['option'] as $key => $value) {
                $options_option .= '<option value="'.$key.'"';
                if (isset($options['value']) && $key == $options['value'] ||
                    (!isset($options['value']) && isset($options['default']) && $key == $options['default']))
                    $options_option .= ' selected="selected"';
                $options_option .= '>'.$value.'</option>';
            }
            unset($options['option']);
            unset($options['value']);
            unset($options['default']);

        }

        foreach ($options as $key => $value)
        {
            if (is_string($key) && (is_string($value) || is_numeric($value)))
            {
                if ($key == 'value')
                    $options_value = $value;
                else
                    $options_html .= ' '.$key.'="'.$value.'"';
            }
        }
    }
?>

<div class="meta-box-item-title">
	<h4><?php if (isset($label)) echo $label; ?></h4>
</div>
<div class="meta-box-item-content">
    <select name="<?php if (isset($name)) echo $name; ?>" id="<?php if (isset($name)) echo $name; ?>"<?php echo $options_html; ?> style="width:100%;">
        <?php echo $options_option; ?>
	</select>
</div>
