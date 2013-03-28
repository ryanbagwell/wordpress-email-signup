<form id="email-signup">

	<ul>
		<li>
			<label for="first-name">First Name</label>
			<input type="text" name="first_name" id="first-name" value="" class="required" />
		</li>

        <li>
            <label for="last-name">Last Name</label>
            <input type="text" name="last_name" id="last-name" value="" class="required" />
        </li>

		<li>
			<label for="email">Enter your email address</label>
			<input type="text" name="email" id="email" value="" class="required email" />
		</li>
	</ul>

	<button type="submit">
        <span class="left"></span>
        Subscribe
        <span class="right"></span>
    </button>

</form>

<div class="success">
    Thanks for signing up!
</div>

<img class="loading" src="/wp-content/plugins/wordpress-email-signup/img/loading.gif" />

<script type="text/javascript">
    jQuery(document).ready(function($) {
        var emailSignup = new EmailSignup('#mail-signup');
    });
</script>

