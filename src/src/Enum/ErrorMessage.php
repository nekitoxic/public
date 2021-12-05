<?php
namespace App\Enum;

enum ErrorMessage: string
{
    case SyntaxError = 'Syntax error';
    case InvalidData = 'Data is not Valid';
}