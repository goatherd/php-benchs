<?php
function throwException() {
    throw new Exception();
}

function throwOtherException() {
    throw new OtherException();
}

class OtherException extends Exception
{

}