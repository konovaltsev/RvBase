<?php

return array(
    '' => array('plural_forms' => 'nplurals=3; plural=(n%10==1 && n%100!=11 ? 0 : n%10>=2 && n%10<=4 && (n%100<10 || n%100>=20) ? 1 : 2);'),

    // StringLength
    "The input is less than %min% characters long" => "Строка меньше разрешенной минимальной длины в %min% символов",
    "The input is more than %max% characters long" => "Строка больше разрешенной максимальной длины в %max% символов",

    // FileSize
    "Maximum allowed size for file is '%max%' but '%size%' detected"  => "Максимально допустимый размер файла %max%, размер загруженного файла %size%",
    "Minimum expected size for file is '%min%' but '%size%' detected" => "Минимально допустимый размер файла %min%, размер загруженного файла %size%",
    "File is not readable or does not exist"                          => "Ошибка чтения файла",

    'The input is not a valid email address. Use the basic format local-part@hostname' => "Недопустимый адрес электронной почты. Введите его в формате имя@домен",
    "The input does not match against pattern '%pattern%'" => 'Значение не соответствует формату',
);
