<?php
/**
 * Created by PhpStorm.
 * User: wsjp
 * Date: 2019/03/16
 * Time: 13:43
 */

declare(strict_types=1);

use WScore\Html\Form;
use PHPUnit\Framework\TestCase;

class FormTest extends TestCase
{
    public function testInput()
    {
        $html = Form::input('radio', 'Html', 'tested');
        $this->assertEquals('<input type="radio" name="Html" id="Html" value="tested">', (string) $html);
    }

    public function testTextArea()
    {
        $html = Form::textArea('Text', 'Area');
        $this->assertEquals('<textarea name="Text" id="Text">Area</textarea>', (string) $html);
    }

    public function testOpen()
    {
        $html = Form::open('test.php');
        $this->assertEquals('<form action="test.php" method="post"' . '>', (string) $html);
    }

    public function testOpenForUpload()
    {
        $html = Form::openForUpload('test.php');
        $this->assertEquals('<form action="test.php" method="post"' . ' enctype="multipart/form-data">', (string) $html);
    }

    public function testClose()
    {
        $html = Form::close();
        $this->assertEquals('</form>', (string) $html);
    }

}
