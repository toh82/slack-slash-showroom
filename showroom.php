<?php

/** @var string */
$token = $_POST['token'];

/** @var string */
$command = $_POST['command'];

/** @var string */
$userName = $_POST['user_name'];

/** @var array */
$information = split(' ', $_POST['text']);

/** @var array */
$allowedShowroomNames = [
  'dev-web',
  'dev-shop',
  'www',
  'shop'
];

/** @var string */
$slackToken = 'yourToken';

/**
 * @param string $id
 */
function getStatus($id)
{
  $fileExists = file_exists('showroom/' . $id . '.lock');

  if ($fileExists) {
    $userName = file_get_contents('showroom/' . $id . '.lock');
    return $id . ' is locked by @' . $userName;
  }

  return $id . ' is free';
}

/**
 * @param string $status
 * @param string $id
 * @param string $userName
 */
function setStatus($id, $status, $userName)
{
  if ($status === 'true') {
    $fileStatus = file_put_contents('showroom/' . $id . '.lock', $userName);

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
  && $token === $slackToken
) {
  $message = 'your command was not valid';

  if(!in_array($information[0], $allowedShowroomNames)){
    echo '-> showroom: given showroom is not allowed.';
    return;
  }

  if(!empty($information[1])) {
    $message = setStatus(
      $information[0],
      $information[1],
      $userName
    );
  } else {
    $message = getStatus($information[0]);
  }

  echo '-> showroom: ' . $message;
  return;
}
