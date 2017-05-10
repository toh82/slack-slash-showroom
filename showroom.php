<?php

/** @var string */
$token = $_POST['token'];

/** @var string */
$command = $_POST['command'];

/** @var array */
$information = split(' ', $_POST['text']);

/** @var array */
$allowedShowroomNames = [
  'dev-web',
  'dev-shop',
  'www',
  'shop'
];

/**
 * @param string $id
 */
function getStatus($id)
{
  $fileExists = file_exists('showroom/' . $id . '.lock');
  if ($fileExists) {
    return $id . ' is locked';
  }

  return $id . ' is free';
}

/**
 * @param string $status
 * @param string $id
 */
function setStatus($id, $status)
{
  if ($status === 'true') {
    $fileStatus = file_put_contents('showroom/' . $id . '.lock', ' ');

    if ($fileStatus === false) {
      return 'not able to write lock file.';
    }

    return 'set '. $id . ' to locked.';
  }

  if ($status === 'false') {
    unlink('showroom/' . $id . '.lock');
    return 'set '. $id . ' to unlocked.';
  }

  return 'your status has to be either true or false';
}

if (
  $command === '/showroom'
  && !empty($information)
  && $token === 'yourSlackToken'
) {
  $message = 'your command was not valid';

  if(!in_array($information[0], $allowedShowroomNames)){
    echo '-> showroom: given showroom is not allowed.';
    return;
  }

  if(!empty($information[1])) {
    $message = setStatus(
      $information[0],
      $information[1]
    );
  } else {
    $message = getStatus($information[0]);
  }

  echo '-> showroom: ' . $message;
  return;
}
