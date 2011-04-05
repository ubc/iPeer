<table>
  <tr>
    <td width="180px">Question Prompt:</td>
    <td><?php echo $this->Form->input('Question.'.$i.'.title', 
                                      array_merge($default_options, 
                                                  array('class' => 'question-title')))?></td>
  </tr>

  <tr>
    <td>Mandatory?</td>
    <td><?php echo $this->Form->input('Question.'.$i.'.required', 
                                      array('label' => false,
                                            'type' => 'checkbox',
                                            'before' => '',
                                            'after' => '',
                                            'between' => '',
                                            'readonly' => $default_options['readonly'],
                                           ))?></td>
  </tr>

  <tr>
    <td>Instruction:</td>
    <td><?php echo $this->Form->input('Question.'.$i.'.instructions', $default_options)?></td>
  </tr>

  <tr>
    <td>Student's Answer Option:</td>
    <td><?php echo $this->Form->input('Question.'.$i.'.response_type', 
                                      array('options' => array('S' => 'Single line of text input box',
                                                               'L' => 'Multiple lines of text input box (Maximum 65535 characters)'),
                                            'type' => 'radio',
                                            'legend' => false,
                                            'before' => '',
                                            'after' => '',
                                            'between' => '',
                                            'separator' => '&nbsp;',
                                            'label' => false,
                                            'readonly' => $default_options['readonly'],
                                            'multiple' => true,
                                           ))?></td>
  </tr>

</table>
