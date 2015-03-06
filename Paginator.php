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
    public $arrows = 'firstAndLast';
    public $arrowsContent = [
        'previewAndNext' => [
            'preview' => '<<',
            'next' => '>>',
        ],
        'firstAndLast' => [
            'first' => '1...',
            'last' => '...:number'
        ]
    ];
    public $href = '?p=';
    public $position = 1;
    public $buttons = [];
    public $class = ['a' => '', 'li' => 'navigator'];
    public $html = "<li class=\":class-li\"><a class=\":class-a\" href=\":href\">:number</a></li>";

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

        $this->quantPages = ceil($this->total / $this->max);
    }

    function execute()
    {
        for ($i = $this->position; $i <= ($this->position + $this->max); $i++) {
            $attr = [
                'href' => $this->href . $i,
                'number' => $i,
            ];

            if($i == $this->position){
                $attr['class-li'] = $this->class['li'].' current';
                $attr['class-a'] = $this->class['a'].' current';
            }

            $this->buttons[] = $this->createLi($attr);
        }

        if ($this->arrows)
            $this->arrowsCreator();
    }

    function createLi($value)
    {
        $html = $this->html;
        $html = str_replace(":href", $value['href'], $html);
        $html = str_replace(":number", $value['number'], $html);
        $html = str_replace(":class-a", isset($value['class-a']) ? $value['class-a'] : $this->class['a'], $html);
        $html = str_replace(":class-li", isset($value['class-li']) ? $value['class-li'] : $this->class['li'], $html);
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
        if (is_array($this->arrows))
            $arrowsContent = $this->arrows;
        else
            $arrowsContent = $this->arrowsContent[$this->arrows];

        $first = 0;
        $last = 1;

        switch ($this->arrows) {
            case 'firstAndLast':
                $first = 'first';
                $last = 'last';
                break;

            case 'previewAndNext':
                $first = 'preview';
                $last = 'next';
                break;
        }


        if ($this->position > 1)
            array_unshift($this->buttons, $this->createLi([
                'number' => str_replace(":number", '1', $arrowsContent[$first]),
                'href' => $this->href . '1']));

        if ($this->position < $this->quantPages)
            array_push($this->buttons, $this->createLi([
                'number' => str_replace(":number", $this->quantPages, $arrowsContent[$last]),
                'href' => $this->href . $this->quantPages]));

    }

}