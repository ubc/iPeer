<?php
/*
 * Pagination Helper CakePHP framework.
 * Copyright (c) 2005 Garrett J. Woodworth : gwoo@rd11.com
 * rd11,inc : http://rd11.com
 *
 * @author      gwoo <gwoo@rd11.com>
 * @version     0.10.6.1835
 *
 */
class PaginationHelper {

	var $helpers = array('Html','Ajax');
	var $_details = array();

	var $total = '0';
	var $style = 'html';
	var $link = '';
	var $show = array();
	var $page = '1';
	var $direction = 'desc';
	var $updateId = 'ajax_update';
	var $loadingId = 'loading';
  var $result_count = null; // the count of the result returned. this may be different from recordCount, which is the whole count.

	/**
	 * Sets the default pagination options.
	 *
	 * @param array $paging an array detailing the page options
	 */
	function set($paging)
	{
		//pr($this->params);
		if(!empty($paging))
		{
			$this->style = isset($paging['style']) ? $paging['style'] : 'html';
			$this->link = (isset($paging['link']) && isset($paging['params'])) ? $paging['link'].$paging['params'] : $paging['link'];
			$this->show = isset($paging['show']) ? $paging['show'] : array();
			$this->page = isset($paging['page']) ? $paging['page'] : '1';
			$this->direction = isset($paging['direction']) ? $paging['direction'] : 'desc';
      $this->result_count = isset($paging['result_count']) ? $paging['result_count'] : null;

			$pageCount = ($paging['limit']!=0) ? ceil($paging['count'] / $paging['limit'] ):0;

			//echo $paging['count'];
			$this->_details = array(
						'page'=>$paging['page'],
						'recordCount'=>$paging['count'],
						'pageCount' =>$pageCount,
						'next'=> ($paging['page'] < $pageCount) ? $paging['page']+1 : '',
						'previousPage'=> ($paging['page']>1) ? $paging['page']-1 : '',
						'limit'=>$paging['limit']
						);

		}

		if(!empty($paging) && $paging['count'] > $paging['show'][0]) {
			return true;
		} else {
            return false;
        }
	}
	/**
	* Displays limits for the query
	*
	* @param string $text - text to display before limits
	* @param string $seperator - display a seperate between limits
	*
	**/
	function show($text=null, $seperator=null, $ajaxObj=null)
	{
		if (empty($this->_details)) {
            return false;
        }
		if (!empty($this->_details['recordCount'])) {
			$t = '';
			$selected = '';
			if(is_array($this->show)) {
				$link = preg_replace('/show=(.*?)&/','',$this->link);
				$link = @ereg_replace('&','&amp;',$link);
				$t .= '<b>' . $text. '</b>';
				$t .= '<select onChange="showLimit(this.value,\'' . $link .
                        $this->_details['page'] . '\',\'' . $ajaxObj . '\');">';
				foreach($this->show as $value) {
					if($value < ($this->_details['recordCount'] + $this->_details['limit'])) {
                        $selected = ($value == $this->_details['limit'] || $this->_details['limit'] == 99999999)
                                        ? "selected" : "";

                        $t .= "<option value=\"$value\" $selected>$value</option>";
					}
				}
				$t .= '</select>';
			}
			return $t;
		} else {
            return false;
        }

	}
	/**
	* Displays current result information
	*
	* @param string $text - text to preceeding the number of results
	*
	**/
	function result($text)
	{
//		if (empty($this->_details)) { return false; }
//		if ( !empty($this->_details['recordCount']) )
//		{
		/*	if($this->_details['recordCount'] > $this->_details['limit'])
			{
				$start_row = $this->_details['page'] > 1 ? ($this->_details['page']-1)*$this->_details['limit']: '1';
				$end_row = ($this->_details['recordCount'] < ($start_row + $this->_details['limit']-1)) ? $this->_details['recordCount'] : ($start_row + $this->_details['limit']-1);
				return $text.$start_row.'-'.$end_row.' of '.$this->_details['recordCount'];
			}
			else
			{
			*/
    $result_count_text = (null == $this->result_count) ? '' : $this->result_count.' of ';
    return $text . $result_count_text . $this->_details['recordCount'];
			//}
	//	}
		//return false;
	}
	/**
	* Returns a list of page numbers seperated by $seperator
	*
	* @param string $seperator - defaults to null
	*
	**/
	function numbers($seperator=null)
	{
		if (empty($this->_details) || $this->_details['pageCount'] == 1) { return false; }
		$t = '';
		$pc = 1;
		  do {
			 if($pc == $this->_details['page'])  {
				$t .= '<em>'.$pc.'</em>';
			 } else {
				if($this->style == 'ajax') {
					$t .= $this->Ajax->link($pc, $this->link.$pc, array("update" => $this->updateId, 'loading' => "Element.show('".$this->loadingId."');", 'complete' => "Element.hide('".$this->loadingId."');", "method"=>"get"));
				} else {
					$t .= $this->Html->link($pc,$this->link.$pc);
				}
			 }
			 $pc++;
		  } while ($pc<=$this->_details['pageCount']);
		return $t;
	}
	/**
	* Returns a "Google style" list of page numbers
	*
	* @param string $separator - defaults to null
	* @param string $prefix - defaults to null. If set, displays prefix before page links.
	* @param int $pageSetLength - defaults to 10. Maximum number of pages to show.
	* @param string $prevLabel - defaults to null. If set, displays previous link.
	* @param string $nextLabel - defaults to null. If set, displays next link.
	*
	**/
	function googleNumbers($separator=null, $prefix=null, $pageSetLength=10, $prevLabel=null, $nextLabel=null)
	{
		if (empty($this->_details) || $this->_details['pageCount'] == 1) { return false; }

		$t = array();

		$modulo = $this->_details['page'] % $pageSetLength;
		if ($modulo)
		{ // any number > 0
			$prevSetLastPage = $this->_details['page'] - $modulo;
		}
		else
		{ // 0, last page of set
			$prevSetLastPage = $this->_details['page'] - $pageSetLength;
		}
		//$nextSetFirstPage = $prevSetLastPage + $pageSetLength + 1;

		if ($prevLabel) $t[] = $this->prev($prevLabel);

		// loops through each page number
		$pageSet = $prevSetLastPage + $pageSetLength;
		if ($pageSet > $this->_details['pageCount']) $pageSet = $this->_details['pageCount'];

		for ($pageIndex = $prevSetLastPage+1; $pageIndex <= $pageSet; $pageIndex++)
		{
			if ($pageIndex == $this->_details['page'])
			{
				$t[] = '<em>'.$pageIndex.'</em>';
			}
			else
			{
				if($this->style == 'ajax')
				{
					$t[] = $this->Ajax->link($pageIndex,$this->link.$pageIndex, array("update" => $this->updateId, 'loading' => "Element.show('".$this->loadingId."');", 'complete' => "Element.hide('".$this->loadingId."');", "method"=>"get"));
				}
				else
				{
					$t[] = $this->Html->link($pageIndex,$this->link.$pageIndex);
				}
			}
		}

		if ($nextLabel) $t[] = $this->next($nextLabel);

		$t = implode($separator, $t);

		return $prefix.$t;
	}
	/**
	* Displays a link to the previous page, where the page doesn't exist then
	* display the $text
	*
	* @param string $text - text display: defaults to next
	*
	**/
	function prev($text='prev')
	{
		if (empty($this->_details)) { return false; }
		if ( !empty($this->_details['previousPage']) )
		{
			if($this->style == 'ajax')
			{
				//return @ereg_replace('&','&amp;',$this->Ajax->link($text, $this->link.$this->_details['previousPage'], array("update" => $this->updateId,  'loading' => "Element.show('".$this->loadingId."');", 'complete' => "Element.hide('".$this->loadingId."');", "method"=>"get")));
				return $this->Ajax->link($text, $this->link.$this->_details['previousPage'], array("update" => $this->updateId,  'loading' => "Element.show('".$this->loadingId."');", 'complete' => "Element.hide('".$this->loadingId."');", "method"=>"get"));
			}
			else
			{
				return @ereg_replace('&','&amp;',$this->Html->link($text,$this->link.$this->_details['previousPage']));
			}
		}
		return false;
	}
	/**
	* Displays a link to the next page, where the page doesn't exist then
	* display the $text
	*
	* @param string $text - text to display: defaults to next
	*
	**/
	function next($text='next')
	{
		if (empty($this->_details)) { return false; }
		if (!empty($this->_details['next']))
		{
			if($this->style == 'ajax')
			{
				//return @ereg_replace('&','&amp;',$this->Ajax->link($text, $this->link.$this->_details['next'], array("update" => $this->updateId, 'loading' => "Element.show('".$this->loadingId."');", 'complete' => "Element.hide('".$this->loadingId."');", "method"=>"get")));
				return $this->Ajax->link($text, $this->link.$this->_details['next'], array("update" => $this->updateId, 'loading' => "Element.show('".$this->loadingId."');", 'complete' => "Element.hide('".$this->loadingId."');", "method"=>"get"));
			}
			else
			{
				return @ereg_replace('&','&amp;',$this->Html->link($text,$this->link.$this->_details['next']));
			}
		}
		return false;
	}

	/**
	* Displays a link for sorting
	*
	*
	* @param string $name - text to display
	* @param array $sort - array with the field to sort and default direction
	*
	**/
	function sortLink($name, $sort, $html_options = null)
	{
		if(isset($sort[0]))
		{
			$newlink = preg_replace('/sort=(.*?)&/','sort='.$sort[0].'&',$this->link.$this->page);
			$sort[1] = ($this->direction == 'desc') ? 'asc' : 'desc';
		}
		else
		{
			$newlink = $this->link.$this->page;
		}

		if(!empty($sort[1]))
		{
			$newlink = preg_replace('/direction=(.*?)&/','direction='.$sort[1].'&',$newlink);
		}

		if($this->style == 'ajax')
		{
			//$newlink = @ereg_replace('&','&amp;',$newlink);
			//$newlink = @ereg_replace('&',';',$newlink);
			return $this->Ajax->link($name, $newlink, array("update" => $this->updateId, 'loading' => "Element.show('".$this->loadingId."');", 'complete' => "Element.hide('".$this->loadingId."');", "method"=>"get"));
		}
		else
		{
			//$newlink = @ereg_replace('&','&amp;',$newlink);
			return $this->Html->link($name, $newlink);
		}
	}

	function deleteLink($id, $text, $confirm = "Are you sure you want to delete?", $replace = null)
	{
		$params = (isset($this->params['pass'])) ? join('/',$this->params['pass']) : null;
		$action = ($replace) ? $replace : $this->action;
		$newlink = preg_replace('/'.$action.'/','delete/'.$id,$this->link.$this->page);

		if($this->style == 'ajax')
		{
			return $this->Ajax->link($text, $newlink, array("update" => $this->updateId, 'loading' => "Element.show('".$this->loadingId."');", 'complete' => "Element.hide('".$this->loadingId."');", "confirm"=>$confirm, "method"=>"get"));
		}
		else
		{
			return $this->Html->link($text, $newlink);
		}
	}

	function statusLink($id, $status, $text, $confirm = "Are you sure you want to delete?", $replace = null)
	{
		$altstatus = ($status === '1') ? 0 : 1;

		$params = (isset($this->params['pass'])) ? '/'.join('/',$this->params['pass']) : null;
		$action = ($replace) ? $replace : $this->action;
		$newlink = preg_replace('/'.$action.'/','status/'.$id.'/'.$altstatus.$params,$this->link.$this->page);

		if($this->style == 'ajax')
		{
			return $this->Ajax->link($text[$status], $newlink, array("update" => $this->updateId, 'loading' => "Element.show('".$this->loadingId."');", 'complete' => "Element.hide('".$this->loadingId."');", "confirm"=>$confirm, "method"=>"get"));
		}
		else
		{
			return $this->Html->link($text[$status], $newlink);
		}
	}

	function current()
	{
		$t = '?';
		foreach($this->params['url'] as $key=>$value)
		{
			$t .= ($key != 'url') ? $key.'='.$value.'&' : null;
		}
		return $t;
	}

}
?>
