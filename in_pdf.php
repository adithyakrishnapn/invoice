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
$pdf = new FPDF();
$pdf->AddPage();
$pdf->Image("img/pdf.jpg", 0, 0, 210,300);
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
  $pdf->Cell(20, 10, $srNo++, 1,0,'C');
  $productTotal = $product['quantity'] * $product['price']; // calculate the row total
  $pdf->Cell(50, 10, $product['product'], 1,0,'C');
  $pdf->Cell(40, 10, $product['quantity'], 1,0,'C');
  $pdf->Cell(50, 10, 'Rs.'.$product['price'], 1,0,'R');
  $pdf->Cell(0, 10, 'Rs.'.$productTotal, 1,0,'R'); // display the row total
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
$pdf->Cell(0, 3, '  * 100% Payment after delivery the materials', 0, 1);
$pdf->Cell(0, 3, '  * Cash/Cheque/NEFT/UPI are accepted', 0, 1);
$pdf->Cell(0, 3, '  * Warranty based material manufacturing company', 0, 1);
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

// output PDF to browser
// generate filename
$filename = 'uploads/invoice/exgst/'.' EXgst '.$id.' '.$name . '.pdf';

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
