<?php
$filename = $_POST['filename'];
if (unlink("uploads/quotation/exgst/$filename")) {
  echo "File deleted successfully.";
} else {
  echo "Error deleting file.";
}
?>
