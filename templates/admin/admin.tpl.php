<div class="wrap">
	<div id="icon-options-general" class="icon32"><br /></div>
	<h2>Email Subscription Options</h2>
	
	<style>
	    #email-signup-admin td label {
            display: inline-block;
            text-align: right;
            margin-right: 10px;
            
	    }
	    #email-signup-admin td p {
	        position: relative;
	    }
	    
	    #email-signup-admin tr.service td {
	        display: none;
	    }
	    #email-signup-admin tr.service.selected td {
	        display: table-row;
	    }
	    
	    
	</style>
	
	<form id="email-signup-admin" method="post">
	<table class="form-table">
	    <tbody>

	        <tr>
                <th scope="row">
                    <label>Widget Title</label>
                </th>
                <td>
                    <input type="text" name="email_signup_default_widget_title" value="<?php echo get_option('email_signup_default_widget_title', ''); ?>" />
                </td>
            </tr>
            
	        <tr colspan="2">
                <th scope="row" colspan="2">
                    Choose a service:
                </th>
            </tr>
            
            <?php foreach( $this->services as $name => $service ):
            ?>
	        <tr class="service<?php if ($service->name == get_option('selected_submit_handler')): ?> selected<?php endif; ?>">
                <th scope="row">
                    <label>
                        <input type="radio" name="selected_submit_handler" value="<?php echo $service->name; ?>" <?php if ($service->name == get_option('selected_submit_handler')): ?>checked="checked"<?php endif; ?> /> 
                        <?php echo $service->name; ?>
                    </label>
                </th>
                <td class="service-settings">
                    <?php foreach ( $service->get_form_fields() as $field ): ?>
                        <p>
                            <label for="<?php echo $field->attrs['id']; ?>"><?php echo $field->attrs['title']; ?></label>
                            <?php echo $field; ?>
                        </p>
                    <?php endforeach; ?> 
                   
                </td>
            </tr>
            <?php endforeach; ?>
	    </tbody>
	</table>
	
	<p class="submit">
	    <input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes" />
	</p>
	</form>
	
	<script>
	    jQuery(document).ready(function($) {
    	    $('tr.service th input[type="radio"]').click(function() {
    	        $(this).parents('tr').find('td').show();
    	        $(this).parents('tr').siblings().find('td').hide();
    	    });
        });
	</script>

</div>

