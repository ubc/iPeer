<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <td class="tablecell2">
    <form name="days_late" action="penalty/save">
      days Late: <br>
      <input type="text" name="day1" /><br />
	  <input type="text" name="day2" /><br />
	  <input type="text" name="day3" /><br />
	  <input type="text" name="day4" /><br />
	  <input type="text" name="day5" /><br />
    </form>
  </td>
  <td class="tablecell2">
    <form>
      mark penalty: <br>
      <input type="text" name="pen1" /><br />
	  <input type="text" name="pen2" /><br />
	  <input type="text" name="pen3" /><br />
	  <input type="text" name="pen4" /><br />
	  <input type="text" name="pen5" /><br />
    </form>
  </td>
  
<tr class="tablecell2">
  <td colspan="3" align="center"><?php echo $this->Form->submit('Save') ?></td>
</tr>

</table>

