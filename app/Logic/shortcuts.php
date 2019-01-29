<?php

function view_front()
{
    return 'front.';
}

function view_layouts_front()
{
    return view_front().'layouts.';
}

function view_main_front()
{
    return view_front().'main.';
}

function view_modals_front()
{
    return view_layouts_front().'modals.';
}
