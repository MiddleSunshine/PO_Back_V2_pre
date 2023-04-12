<?php

class DateController extends Base {
    public function Now()
    {
        self::returnActionResult([
            'Year'=>date('Y'),
            'Month'=>date('n'),
            'Day'=>date('j'),
            'Hour'=>date('G'),
            'Min'=>intval(date('i')),
        ]);
    }
}