
<div class="meta-box-item-title">
	<h4><?php if (isset($label)) echo $label; ?></h4>
</div>
<div class="meta-box-item-content">

<?php
    if (isset($options) && is_array($options))
    {
        if (isset($options['option']) && !empty($options['option']))
        {

            $html = '';
            foreach ($options['option'] as $key => $value) {

                $html .= '<p>';
                $html .= '<input type="radio"';
                if (isset($name))
                {
                    $html .= ' id="'.$name.'_'.$key.'"';
                    $html .= ' name="'.$name.'"';
                }
                if (isset($options['value']) && $key == $options['value'] ||
                    (!isset($options['value']) && isset($options['default']) && $key == $options['default']))
                    $html .= ' checked="checked"';

                $html .= ' value="'.$key.'" />';
                $html .= '<label';
                if (isset($name))
                {
                    $html .= ' for="'.$name.'_'.$key.'"';
                }
                $html .= ' >'.$value.'</label>';
                $html .= '</p>';
            }

            echo $html;
        }
    }
?>
</div>
