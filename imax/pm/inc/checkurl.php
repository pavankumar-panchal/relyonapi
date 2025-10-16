<?php

$submit = $_POST['submit'];

switch($submit)
{

	case "url":
	
		$form_url = $_POST['form_url']; //'http://etds-payroll-salary-software-india.com/training/registration-form-12thjuly-blr.docx';
		// We preform a bit of filtering 
    	$file = strip_tags($form_url); 
		$file = trim ($form_url); 
		
		#echo "file ". strlen($file);
		
		if(strlen($file)==0)
		{
			$msg = "Enter Path URL ";
			$msg .= "#0";
		}
		else
		{
			$file_headers = @get_headers($file);
		
			if($file_headers[0] == 'HTTP/1.1 404 Not Found') 
			{
				$exists = false;
				$msg =  "Sorry, Please Enter Valid 'URL' . . .!!";
				$msg .= "#0";
			}
			else 
			{
				$exists = true;
				$msg = "URL Is Avaiable . . .!!";
				
				$headers = get_headers("$file");
				$content_length = 1;
				
				foreach ($headers as $h)
				{
					preg_match('/Content-Length: (\d+)/', $h, $m);
					if (isset($m[1]))
					{
						$content_length = (int)$m[1];
						break;
					}
				}
			
				#echo "Bytes = ".$content_length."<br/>"; 
				
				$bytes = ( int )$content_length;
				$kb = round( $bytes / ( int )1024);
				$megs = round( $bytes / ( int )1024 / ( int )1024, 4 );  
			
				$msg .=  "#" .$kb;
				/*echo  "MB ".$megs;*/
				}
		}
		echo ($msg);
		break;
		
}

?>