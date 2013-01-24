<div class="wrap">
	<div id="icon-options-general" class="icon32"><br /></div>
	<h2>Email Subscription Options</h2>
	
	<form method="post">
	<table class="form-table">
	    <tbody>
	        <tr>
                <th scope="row">
                    <label>Default Form Template</label>
                </th>
                <td>
                    <select name="default_form_template">
                        <option></option>
                    </select>
                </td>
            </tr>
            
	        <tr colspan="2">
                <th scope="row" colspan="2">
                    Choose a a submission handler:
                </th>
            </tr>            
            
            <?php foreach( $this->connectors as $connector ):
                $c = new $connector();
            ?>
	        <tr>
                <th scope="row">
                    <label>
                        <input type="radio" name="selected_submit_handler" value="<?php echo $c->name; ?>" <?php if ($c->name == get_option('selected_submit_handler')): ?>checked="checked"<?php endif; ?>/> 
                        <?php echo $c->name; ?>
                    </label>
                </th>
                <td>
                    <?php echo implode('<br />', $c->get_form_fields()); ?>
                </td>
            </tr>
            <?php endforeach; ?>
	    </tbody>
	</table>
	
	<p class="submit">
	    <input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes" />
	</p>
	
	</form>

</div>

