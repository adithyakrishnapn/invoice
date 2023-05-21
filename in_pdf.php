<?php
require('fpdf/fpdf.php');

// read the PDF data from the form
$pdfData = json_decode($_POST['pdf-data'], true);

$name = $_POST['client'];
$email=$_POST['mail'];
$id = $_POST['invoice_no'];
$daddress=$_POST['daddress'];
$current_date = date('d-m-Y');


$financialYearStart = date('Y-04-01', strtotime('-1 year'));
$financialYearEnd = date('Y-03-31');

$currentYear = date('Y');
$nextYear = date('Y', strtotime('+1 year'));
$invoiceId = 'ET/ ' . $currentYear . '-' . $nextYear . '/' . 'IN- '.$id;


//sql connection and insertion
include('config.php');

// Insert the data into the database
$sql = "INSERT INTO ingst(client_name, email, address, daddress, total) VALUES ('$name', '$email', '$daddress','".$pdfData['address']."',".$pdfData['total'].")";
//select Max id

// create PDF document
class PDF extends FPDF {
	function Header(){
		if($this->PageNo()==1){
		    $this->Image("img/pdf.jpg", 0, 0, 210,300);
		}
		else{
			$this->Image("img/pdfend.jpg", 0, 0, 210,300);
		}
		
	}
	function Footer(){
		
		//Go to 1.5 cm from bottom
		$this->SetY(-28);
				
		$this->SetFont('Arial','',8);
		
		//width = 0 means the cell is extended up to the right margin
		$this->Cell(0,10,'Page '.$this->PageNo()." / {pages}",0,0,'C');
	}
}


//A4 width : 219mm
//default margin : 10mm each side
//writable horizontal : 219-(10*2)=189mm

$pdf = new PDF('P','mm','A4'); //use new class

//define new alias for total page numbers
$pdf->AliasNbPages('{pages}');
$pdf->AddPage();

// add address and email
$pdf->SetFont('Arial', 'B', 20);
$pdf->SetTextColor(255, 0, 0); // set font color to red
$pdf->Cell(0, 40, 'INVOICE', 0, 1,'L');
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(0, 0, 255); // blue color
$pdf->Cell(0, 20, 'INV.NO: ' . $invoiceId, 0, 0,'L');
$pdf->SetFont('Arial', 'B', 13);
$pdf->Cell(0, 20, 'DATE : '.$current_date, 0, 1,'R');
$pdf->SetFont('Arial', 'B', 13);
$pdf->SetTextColor(255, 0, 0); // set font color to red
$pdf->Cell(0, 5, 'Client-Name & Address', 0, 0);
$pdf->Cell(0, 5, 'Delivery-Address', 0, 1,'R');
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0, 0, 255); // blue color
$pdf->Cell(0, 10, $name, 0, 0);
$pdf->Cell(0, 10, $name, 0, 1,'R');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(100, 5, $pdfData['address'], 0, 0);
$pdf->Cell(0, 5, $daddress, 0, 1,'R');



$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(255, 0, 0); // set font color to red
$pdf->Cell(30, 15, 'Email:', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0, 0, 255); // blue color
$pdf->Cell(60, 15, $email, 0, 1);



$pdf->SetTextColor(255, 0, 0); // set font color to red
$pdf->Cell(20, 10, 'S.No:', 1, 0, 'C');
$pdf->Cell(50, 10, 'Description', 1, 0, 'C');
$pdf->Cell(40, 10, 'Qty', 1, 0, 'C');
$pdf->Cell(50, 10, 'Unit Price', 1, 0, 'C');
$pdf->Cell(0, 10, 'Total', 1, 0, 'C'); // add a new column for row total
$pdf->Ln();


$pdf->SetTextColor(0, 0, 255); // blue color
$srNo = 1;



foreach ($pdfData['products'] as $product) {
  
	$cellWidth=50;//wrapped cell width
	$cellHeight=5;//normal one-line cell height
	
	//check whether the text is overflowing
	if($pdf->GetStringWidth($product['product']) < $cellWidth){
		//if not, then do nothing
		$line=1;
	}else{
		//if it is, then calculate the height needed for wrapped cell
		//by splitting the text to fit the cell width
		//then count how many lines are needed for the text to fit the cell
		
		$textLength=strlen($product['product']);	//total text length
		$errMargin=10;		//cell width error margin, just in case
		$startChar=0;		//character start position for each line
		$maxChar=0;			//maximum character in a line, to be incremented later
		$textArray=array();	//to hold the strings for each line
		$tmpString="";		//to hold the string for a line (temporary)
		
		while($startChar < $textLength){ //loop until end of text
			//loop until maximum character reached
			while( 
			$pdf->GetStringWidth( $tmpString ) < ($cellWidth-$errMargin) &&
			($startChar+$maxChar) < $textLength ) {
				$maxChar++;
				$tmpString=substr($product['product'],$startChar,$maxChar);
			}
			//move startChar to next line
			$startChar=$startChar+$maxChar;
			//then add it into the array so we know how many line are needed
			array_push($textArray,$tmpString);
			//reset maxChar and tmpString
			$maxChar=0;
			$tmpString='';
			
		}
		//get number of line
		$line=count($textArray);

	}
	
	//write the cells
	$pdf->Cell(20,($line * $cellHeight),$srNo++,1,0,'C'); //adapt height to number of lines
    $productTotal = $product['quantity'] * $product['price']; // calculate the row total
	

	//use MultiCell instead of Cell
	//but first, because MultiCell is always treated as line ending, we need to 
	//manually set the xy position for the next cell to be next to it.
	//remember the x and y position before writing the multicell
	$xPos=$pdf->GetX();
	$yPos=$pdf->GetY();
    $pdf->setFillColor(255,255,255); 
	$pdf->MultiCell($cellWidth,$cellHeight,$product['product'],'LRT',0,'C');
	
	//return the position for next cell next to the multicell
	//and offset the x with multicell width
	$pdf->SetXY($xPos + $cellWidth , $yPos);

	
	$pdf->Cell(40,($line * $cellHeight),$product['quantity'],1,0,'C'); //adapt height to number of lines
    $pdf->Cell(50,($line * $cellHeight),'Rs.'.$product['price'],1,0,'R'); //adapt height to number of lines
    $pdf->Cell(0,($line * $cellHeight),'Rs.'.$productTotal, 1,1,'R'); //adapt height to number of lines
	

    $pdf->Cell(0, 0,'', 1, 0, 'C');
    $pdf->Ln();
	
}



  // add totals
  $pdf->SetFont('Arial', '', 13);
  $pdf->Cell(100, 10, 'Sub Total:', 1, 0, 'R');
  $pdf->Cell(0, 10, 'Rs.'.$pdfData['subTotal'], 1, 1,'R');
  
  $pdf->SetFont('Arial', 'B', 17);
  $pdf->Cell(100, 10, 'Total:', 1, 0, 'R');
  $pdf->Cell(0, 10, 'Rs.'.$pdfData['subTotal'], 1, 0, 'R');



$pdf->Cell(0, 20, '', 0, 1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(255, 0, 0); // set font color to red
$pdf->Cell(0, 10, 'Terms & Conditions :', 0, 1);
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 255); // blue color

// Define the text to be displayed
$terms = $_POST['term'];

// Loop through each <li> element and add it to the text
foreach ($terms as $term) {
	$pdf->Cell(0, 3, $term, 0, 1);
  }

$pdf->Cell(0, 13, '', 0, 1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(255, 0, 0); // set font color to red
$pdf->Cell(0, 10, 'Account Details  :', 0, 1);
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 255); // blue color
$pdf->Cell(0, 3, '  Account Name : ELETECHTRONICS', 0, 1);
$pdf->Cell(0, 3, '  Bank Name : Central Bank of India', 0, 1);
$pdf->Cell(0, 3, '  Account Number : 5369414371', 0, 1);
$pdf->Cell(0, 3, '  IFSC Code : CBIN0282106', 0, 1);
$pdf->Cell(0, 3, '  Branch Name : NANJUNDAPURAM', 0, 1);
$pdf->Cell(0, 10, '*This is computer generated invoice no signature required(Authorized by ELETECHTRONICS)', 0, 0, 'C');


// output PDF to browser
// generate filename
$filename = 'uploads/invoice/exgst/'.' INV '.$id.' '.$name . '.pdf';

// output PDF to file
$pdf->Output($filename, 'F');
$pdf->Output($filename, 'D');

if (mysqli_query($conn, $sql)) {
  echo "New record created successfully";
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);

// return success response
echo json_encode(['success' => true]);

// Include PHPMailer library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require 'sendemail/phpmailer/src/Exception.php';
require 'sendemail/phpmailer/src/PHPMailer.php';
require 'sendemail/phpmailer/src/SMTP.php';

// Create a new PHPMailer instance
$mail = new PHPMailer;

// Set up SMTP
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPSecure = 'tls';
$mail->SMTPAuth = true;
$mail->Username = 'dragoncorexgamer@gmail.com';
$mail->Password = 'gdscximiixmtwtwt';

// Set up email content
$mail->setFrom('dragoncorexgamer@gmail.com', 'COMPANY');
$mail->addAddress($email, $name);
$mail->addAddress('dragoncorexgamer@gmail.com', 'DGX');
$mail->Subject = 'Invoice';
$mail->Body = 'Please find attached your invoice.';
$mail->addAttachment($filename);

// Send the email
if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
