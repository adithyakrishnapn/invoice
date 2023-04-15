<?php
$filename = $_POST['filename'];
if (unlink("uploads/quotation/ingst/$filename")) {
  echo "File deleted successfully.";
} else {
  echo "Error deleting file.";
}
?>
