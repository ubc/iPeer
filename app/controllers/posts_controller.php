<?php
class PostsController extends AppController {

    var $name ='Posts';

    var $layout = 'ajax_blog';

    var $helpers = array('Html', 'Javascript', 'Ajax', 'Time');

    function index ()
    {
        //print_r($this);
        $this->set('data', $this->Post->findAll(null, null, 'id'));
    }

    function view ($id)
    {
        //layout to ajax is a cheap way to render partials.
        $this->layout = 'ajax';
        $this->set('data', $this->Post->read());
    }

    function delete($id = null)
    {
        if (isset($this->params['form']['id']))
        {
            $id = intval(substr($this->params['form']['id'], 5));
        }   //end if
        $this->Post->del($id);
        //delete should return same as add
        $this->render('add', 'ajax');
    }

    function add ()
    {
        if (!empty($this->params['data']))
        {
            foreach (($this->params['data']['Post']) as $k => $v)
            {
                $this->params['data']['Post'][$k] = htmlspecialchars($v);
            }
            if ($this->Post->save($this->params['data']))
            {
                $this->render('add', 'ajax');
            }
            else
            {
                //do nothing
            }
        }
    }

    function edit($id = null) {
        $this->layout = 'ajax';
        if(empty($this->params['data']))
        {
            //not submitted thus we should give the form
            $this->params['data'] = $this->Post->read();
            $this->render();
        }
        else
        {
            //if it's saving, it should be return the table again.
            //form is fine since it stays there.
            foreach (($this->params['data']['Post']) as $k => $v)
            {
                $this->params['data']['Post'][$k] = htmlspecialchars($v);
            }
            $this->Post->save($this->params['data']);
            $this->render('add', 'ajax');
        }
    }

    function search()
    {
        $this->layout = 'ajax';
    }
}   ///:~
