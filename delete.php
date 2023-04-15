<?php
$filename = $_POST['filename'];
if (unlink("uploads/invoice/ingst/$filename")) {
  echo "File deleted successfully.";
} else {
  echo "Error deleting file.";
}
?>
