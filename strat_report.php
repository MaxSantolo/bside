<?php
include 'tech/connect.php';

$sql="SELECT * FROM cdr_accessi_sum";
?>

<html>

<?php
$result = mysql_query("SELECT * FROM cdr_accessi_sum");
$set = array();
while ($record = mysql_fetch_object($result)) {
$set[$record->color][] = $record;
}
foreach ($set as $color => $records) {
print "\n";
print "{$color}\n";
print "Name Size Color\n";
foreach ($records as $record) {
print render($record);
}
print "\n";
}
?>

</html>