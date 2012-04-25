<table id="table_id">
    <thead>
        <tr>
<?php
if (!empty($classList)) {
  $sample = $classList[0];
  foreach ($sample as $key => $val) {
    echo "<th>$key</th>";
  }
}
?>
        </tr>
    </thead>
    <tbody>

<?php
foreach ($classList as $person) {
  echo "<tr>";
  foreach ($person as $val) {
    echo "<td>$val</td>";
  }
  echo "</tr>";
}
?>

    </tbody>
</table>

<script type="text/javascript">
jQuery(document).ready(function() {
  jQuery('#table_id').dataTable( {
    "sPaginationType" : "full_numbers"
  } );
} );
</script>
