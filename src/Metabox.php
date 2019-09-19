<?php

namespace AndreKeher\WPDP;

class Metabox
{
    private $id;
    private $title;
    private $postType;
    private $formFunction;
    private $saveFunction;

    public function __construct($id, $title, $postType)
    {
        $this->id = $id;
        $this->title = $title;
        $this->postType = (array) $postType;
    }

    public function init()
    {
        add_action('add_meta_boxes', function () {
            if (empty($this->formFunction)) {
                $this->formFunction = function () {
                };
            }
            add_meta_box($this->id, $this->title, $this->formFunction, $this->postType);
        });
        add_action('save_post', function () {
            global $typenow;
            if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || (defined('DOING_AJAX') && DOING_AJAX)) {
                return false;
            }
            if ($_POST && in_array($typenow, $this->postType)) {
                call_user_func($this->saveFunction);
            }
        });
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setPostType($postType)
    {
        $this->postType = $postType;
    }

    public function setFormFunction(callable $formFunction)
    {
        $this->formFunction = $formFunction;
    }

    public function setSaveFunction(callable $saveFunction)
    {
        $this->saveFunction = $saveFunction;
    }
}
