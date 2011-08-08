<tr class="tablecell2">
  <td id="creator_label">Creator:</td>
  <td align="left" colspan="2">
  <?php echo $this->element('users/user_info', 
                            array('data' => array('id' => $data['creator_id'],
                                                  'full_name' => $data['creator'])));?>
  </td>
</tr>

<tr class="tablecell2">
  <td id="created_label">Create Date:</td>
  <td align="left" colspan="2"><?php if (!empty($data['created'])) echo ''.$data['created'].''; ?></td>
</tr>

<tr class="tablecell2">
  <td id="updater_label">Updater:</td>
  <td align="left" colspan="2">
  <?php echo $this->element('users/user_info', 
                            array('data' => array('id' => $data['updater_id'],
                                                  'full_name' => $data['updater'])));?>
  </td>
</tr>

<tr class="tablecell2">
  <td id="updated_label">Update Date:</td>
  <td align="left" colspan="2"><?php if (!empty($data['modified'])) echo ''.$data['modified'].''; ?></td>
</tr>
