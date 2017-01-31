<?php
if (!defined('ABSPATH'))
  exit;

// answer only for 20 seconds after calling
if (false === is_input_integer('time') || (time() - get_input_integer('time')) > 20) {
  header("HTTP/1.0 403 Forbidden");
  die('wrong request');
}

if ('count' === get_input_string('f')) {
  $cpd_funcs = ['show',
                'getReadsAll', 'getReadsToday', 'getReadsYesterday', 'getReadsLastWeek', 'getReadsThisMonth',
                'getUserAll', 'getUserToday', 'getUserYesterday', 'getUserLastWeek', 'getUserThisMonth',
                'getUserPerDay', 'getUserOnline', 'getFirstCount'];

  if (true === is_input_integer('cpage')) {
    $page = get_input_integer('cpage');

    $count_per_day->count('', $page);
    foreach ($cpd_funcs as $f) {
      if (($f == 'show' && $page) || $f != 'show') {
        echo $f . '===';
        if ($f == 'getUserPerDay')
          echo $count_per_day->getUserPerDay($count_per_day->options['dashboard_last_days']);
        else if ($f == 'show')
          echo $count_per_day->show('', '', false, false, $page);
        else
          echo $count_per_day->{$f}();
        echo '|';
      }
    }
  }

}
