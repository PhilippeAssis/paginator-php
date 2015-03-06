<?php

/**
 * Created by PhpStorm.
 * User: philippe
 * Date: 06/03/15
 * Time: 09:15
 */
class Paginator
{


    public $max = 5;
    public $total = null;
    public $query = 'p';
    public $quantPages = null;
    public $content = '';
    public $arrows = [
        "<li class=\":class-li\"><a class=\":class-a\" href=\":href:last\">last</a></li>",
        "<li class=\":class-li\"><a class=\":class-a\" href=\":href:next\">next</a></li>"
    ];

    public $href = '?p=';
    public $position = 1;
    public $buttons = [];
    public $class = ['a' => '', 'li' => 'navigator', 'current' => ''];
    public $html = "<li class=\":class-li :class-current\"><a class=\":class-a\" href=\":href:number\">:number</a></li>";

    function __construct($position = false)
    {
        if ($position)
            $this->position = $position;
        elseif (isset($_GET[$this->query]))
            $this->position = $_GET[$this->query];
    }

    function calc($total, $max = false, $position = false)
    {
        if ($max)
            $this->max = $max;
        if ($position)
            $this->position = $position;

        $this->total = $total;

        $this->end = $this->quantPages = ceil($this->total / $this->max);
        $this->first = 1;
        $this->last = $this->position - 1;
        $this->next = 20;
        $this->number = $this->position;

    }

    function execute()
    {
        for ($i = $this->position; $i <= ($this->position + $this->max); $i++) {
            $this->number = $i;
            $this->class['current'] = '';

            if($i == $this->position)
                $this->class['current'] = 'current';

            $this->buttons[] = $this->createLi();
        }

        $this->number = $this->position;

        if ($this->arrows)
            $this->arrowsCreator();
    }

    function createLi()
    {
        $html = $this->replaces($this->html);
        return $html;
    }

    function navigator()
    {
        $this->execute();
        return PHP_EOL . implode(PHP_EOL, $this->buttons) . PHP_EOL;
    }

    function li()
    {
        echo $this->navigator();
    }

    function ul($class = 'paginator')
    {
        $buttons = $this->navigator();
        echo "<ul class=\"$class\">$buttons</ul>";
    }


    function arrowsCreator()
    {
        if ($this->position > 1)
            array_unshift($this->buttons,$this->replaces($this->arrows[0]));
        if ($this->position < $this->quantPages)
            array_push($this->buttons,$this->replaces($this->arrows[1]));
    }

    function replaces($item){

        $item = str_replace(':position',$this->position,$item);
        $item = str_replace(':next',$this->next,$item);
        $item = str_replace(':last',$this->last,$item);
        $item = str_replace(':class-a',$this->class['a'],$item);
        $item = str_replace(':class-li',$this->class['li'],$item);
        $item = str_replace(':class-current',$this->class['current'],$item);
        $item = str_replace(':href',$this->href,$item);
        $item = str_replace(':content',$this->content,$item);
        $item = str_replace(':number',$this->number,$item);

        return $item;
    }

}