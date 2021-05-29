<?php
require_once("../header.php");
?>

<?php if ($data['status'] === true ): ?>
<h3>Successfully imported your CSV file into a DB table with same name!</h3>
<?php else: ?>
<h3 style="color:red;">There was a problem uploading your CSV file</h3>
<?php endif;?>
