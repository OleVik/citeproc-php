<?php

namespace Seboettg\CiteProc\Rendering;


use Seboettg\CiteProc\CiteProc;
use Seboettg\CiteProc\Context;
use Seboettg\CiteProc\Style\Citation;
use Seboettg\CiteProc\Style\Macro;

class MacroTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
    }

    public function testRender()
    {
        $xml = '<style><macro name="title"><choose><if type="book"><text variable="title" font-style="italic"/></if><else><text variable="title"/></else></choose></macro><citation><layout delimiter="; "><text macro="title"/></layout></citation></style>';
        $data = json_decode('[{"title":"Ein herzzerreißendes Werk von umwerfender Genialität","type":"book"},{"title":"Ein nicht so wirklich herzzerreißendes Werk von umwerfender Genialität","type":"thesis"}]');

        $styleNode = new \SimpleXMLElement($xml);

        $macroNode = null;
        $citationNode = null;

        foreach ($styleNode as $node) {
            if ($node->getName() === "macro") {
                $macroNode = $node;
                continue;
            }
            if ($node->getName() === "citation") {
                $citationNode = $node;
            }
        }

        $macro = new Macro($macroNode);
        $citation = new Citation($citationNode);
        CiteProc::setContext(new Context());

        CiteProc::getContext()->addMacro($macro->getName(), $macro);

        $actual = $citation->render($data);

        $expected = '<span style="font-style: italic" >Ein herzzerreißendes Werk von umwerfender Genialität</span>; '.
            'Ein nicht so wirklich herzzerreißendes Werk von umwerfender Genialität';

        $this->assertEquals($expected, $actual);
    }


}
