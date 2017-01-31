<?php

/* safe $_SERVER substitution. */
function get_input_server($attribute, $filter = FILTER_UNSAFE_RAW, $flag = FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH) {
  return filter_input(INPUT_SERVER, $attribute, $filter, $flag);
}

function is_input_server($attribute) {
  $tmp = get_input_server($attribute);

  return false !== $tmp && null !== $tmp;
}

function get_input_server_string($attribute) {
  return filter_input(INPUT_SERVER, $attribute, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
}

/* -- */

/* safe $_POST /$_GET substitution. */


function get_input($attribute, $filter = FILTER_UNSAFE_RAW, $flag = 0) {
  $items = [filter_input(INPUT_POST, $attribute, $filter)
            , filter_input(INPUT_GET, $attribute, $filter)];

  $items = array_filter($items, function ($item) {
    return (null !== $item) && (false !== $item);
  });

  array_push($items, null); /* null value */

  $items = array_shift($items);

  return $items;
}

function is_input($attribute, $filter = FILTER_UNSAFE_RAW, $flag = 0) {
  $tmp = get_input($attribute, $filter, $flag);

  return false !== $tmp && null !== $tmp;
}

/* special cases of $_POST/$_GET input, limit allowed input + sanitizing as much as possible. */


//allows: number,minus,plus.
function get_input_integer($attribute) { return get_input($attribute, FILTER_SANITIZE_NUMBER_INT); }

function is_input_integer($attribute) { return is_input($attribute, FILTER_VALIDATE_INT); }


//chart data uses `Y-m-D` date format, which is exactly the SAME as the integer validation/sanitize.
function get_input_chartdate($attribute) { return get_input_integer($attribute); }

function is_input_chartdate($attribute) { return is_input_integer($attribute); }


//by default not allowing HTTP-Header to have characters below 32 or above 127. this is not applied to POST-carry data which can be anything. it WILL remove \n (char code 10) and \r (char code 13) but we don't expect to find those in HTTP-headers anyway...
function get_input_string($attribute) {
  return get_input($attribute, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
}

?>