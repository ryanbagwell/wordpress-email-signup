<?php

class FormField {
	public $classes = array();
	public $required = false;
	
	function FormField($name,$label,$attrs = array(),$required = false) {
		$this->name = $name;
		$this->label = $label;
		$this->attrs = $attrs;
		$this->required = $required;
		
		if ( $_POST && $this->is_empty() )
			$this->classes[] = 'empty';
			
		if ($this->required && $this->is_empty())
			$this->classes[] = 'invalid';
			
		if ( $_POST && !$this->is_valid() )
			$this->classes[] = 'invalid';
		
	}
	
	function is_empty() {
		if ( empty($_POST[$this->name]) )
			return true;
			
		return false;
	}
	
	function is_valid() {
		return true;
	}

}

class TextField extends FormField {
	public $type = "text";

	function TextField($name, $label, $attrs = array(), $required = false) {
		
		parent::__construct($name, $label, $attrs, $required);
		
		$defaults = array(
			'title' => $label,
			'id' => strtolower(str_replace(' ','-',$label)),
			'type' => $this->type,
			'name' => $name,
			'value' => ( $_POST ) ? $_POST[ $name ] : '',
			'class' => implode( ' ' , $this->classes ),
		);

		$this->attrs = array_merge($defaults, $attrs);

		$html = "";

		foreach($this->attrs as $key => $value) {
			$html .= "$key='$value'"; 
		}

		$this->html = "<input $html />";
	
	}	
	
	public function __toString() {
		return $this->html;
	}
	
}

class PasswordField extends TextField {
	public $type = "password";

	function PasswordField($name,$label,$attrs = array(), $required = false) {
		return parent::__construct($name,$label,$attrs,$required);
	}
	
}

class EmailField extends TextField {
	public $type = "text";

	function EmailField($name,$label,$attrs = array(),$required) {
		parent::__construct($name,$label,$attrs,$required);

		return $this;	
	}
	
	function is_valid() {
		
		return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $_POST[ $this->name ]);
		
	}
	
}

class CreditCardField extends TextField {
	public $type = "password";

	function CreditCardField($name,$label,$attrs = array(),$required) {
		$this->name = $name;

		$attrs = array_merge(array('maxlength' => '16'),$attrs);

		parent::__construct($name,$label,$attrs,$required);

		return $this;
		
	}
	
	function is_valid() {
		
		$valid =  preg_match('/^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6011[0-9]{12}|3(?:0[0-5]|[68][0-9])[0-9]{11}|3[47][0-9]{13})$/', $_POST[ $this->name ] );

	}
	
}

class SelectField extends FormField {
	public $choices = array();
	private $type = "select";
	private $html = "";

	function SelectField($name, $label, $attrs = array(), $required = false, $choices = null ) {

		parent::__construct($name,$label,$attrs,$required);

		if ( $choices )
			$this->choices = $choices;
		
		$defaults = array(
			'title' => $label,
			'id' => strtolower(str_replace(' ','-',$label)),
			'name' => $name,
			'class' => implode( ' ' , $this->classes ),
		);

		$attrs = array_merge($defaults, $attrs);

		foreach($attrs as $key => $value ) {
			$this->html .= "$key='$value'"; 
		}

		$this->html = "<select $this->html />\r\n<option value=''>-- Select --</option>";
		
		foreach($this->choices as $key => $value) {
			$selected = '';
			if ( $_POST )
				$selected = ( $_POST[ $name ] == $key ) ? ' selected="selected"' : '';
			$this->html .= "\r\n<option value='$key'$selected>$value</option>"; 
		}
		
		$this->html .= "</select>";
		

	}	
	
	public function __toString() {
		return $this->html;
	}
	
	public function is_valid() {

		if (array_key_exists( $_POST[ $this->name ], $this->choices ))
			return true;
			
		return false;

	}	

}


class USStateField extends SelectField {
	
    public $choices = array('AL'=>"Alabama",
                    'AK'=>"Alaska",  
                    'AZ'=>"Arizona",  
                    'AR'=>"Arkansas",  
                    'CA'=>"California",  
                    'CO'=>"Colorado",  
                    'CT'=>"Connecticut",  
                    'DE'=>"Delaware",  
                    'DC'=>"District Of Columbia",  
                    'FL'=>"Florida",  
                    'GA'=>"Georgia",  
                    'HI'=>"Hawaii",  
                    'ID'=>"Idaho",  
                    'IL'=>"Illinois",  
                    'IN'=>"Indiana",  
                    'IA'=>"Iowa",  
                    'KS'=>"Kansas",  
                    'KY'=>"Kentucky",  
                    'LA'=>"Louisiana",  
                    'ME'=>"Maine",  
                    'MD'=>"Maryland",  
                    'MA'=>"Massachusetts",  
                    'MI'=>"Michigan",  
                    'MN'=>"Minnesota",  
                    'MS'=>"Mississippi",  
                    'MO'=>"Missouri",  
                    'MT'=>"Montana",
                    'NE'=>"Nebraska",
                    'NV'=>"Nevada",
                    'NH'=>"New Hampshire",
                    'NJ'=>"New Jersey",
                    'NM'=>"New Mexico",
                    'NY'=>"New York",
                    'NC'=>"North Carolina",
                    'ND'=>"North Dakota",
                    'OH'=>"Ohio",  
                    'OK'=>"Oklahoma",  
                    'OR'=>"Oregon",  
                    'PA'=>"Pennsylvania",  
                    'RI'=>"Rhode Island",  
                    'SC'=>"South Carolina",  
                    'SD'=>"South Dakota",
                    'TN'=>"Tennessee",  
                    'TX'=>"Texas",  
                    'UT'=>"Utah",  
                    'VT'=>"Vermont",  
                    'VA'=>"Virginia",  
                    'WA'=>"Washington",  
                    'WV'=>"West Virginia",  
                    'WI'=>"Wisconsin",  
                    'WY'=>"Wyoming");

	function USStateField($name, $label, $attrs = array(), $required = false) {
		parent::__construct($name,$label,$attrs,$required);
	}

	
	function is_valid() {

		if (array_key_exists( $_POST[ $this->name ], $this->choices ))
			return true;
			
		return false;

	}
	
}

class MonthField extends SelectField {
	
    public $choices = array(
					'01'=>"Jan",
					'02' => 'Feb',
					'03' => 'March',
					'04' => 'April',
					'05' => 'May',
					'06' => 'June',
					'07' => 'July',
					'08' => 'August',
					'09' => 'Sept',
					'10' => 'Oct',
					'11' => 'Nov',
					'12' => 'Dec',
				);
  

	function MonthField($name, $label, $attrs = array(), $required = false) {
		
		parent::__construct($name,$label,$attrs,$required);
		
	}
	
}


class CreditCardYearField extends SelectField {
    public $choices = array();
	public $num_choices = 10;

	function CreditCardYearField($name, $label, $attrs = array(), $required = false) {
		
		$this->set_years();
		
		parent::__construct($name,$label,$attrs,$required);
		
	}
	
	function set_years() {
		
		$i = 0;
        $start = (int)date("Y");
        while ( $i <= $this->num_choices ) {

			$this->choices[ (string)($start + $i) ] = $start + $i;
            $i++;
        }
		
	}
	
}

class CreditCardTypeField extends SelectField {
	
    public $choices = array(
		'VISA' => 'Visa',
		'MASTERCARD' => 'Mastercard',
		'AMEX' => 'American Express',
		'DISCOVER' => 'DIscover',
	);

	function CreditCardTypeField($name, $label, $attrs = array(), $required = false) {
		
		parent::__construct($name,$label,$attrs,$required);
		
	}
	
}