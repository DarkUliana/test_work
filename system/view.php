<?php


class View
{

    function generate($content_view, $template_view, $data = null)
    {

        if (is_array($data)) {
            extract($data);
        }

        include DOC_ROOT . '/app/views/' . $template_view;
    }
}