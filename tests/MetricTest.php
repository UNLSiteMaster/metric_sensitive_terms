<?php
namespace SiteMaster\Plugins\Metric_Sensitive_Terms;

use SiteMaster\Core\Auditor\Parser\HTML5;

class MetricTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function getSensitiveTermsOccurrences()
    {
        $metric = new Metric('sensitive_terms');

        $xpath = $this->getTestXPath('sensitive_terms.html');
        $result = $metric->getSensitiveTermsOccurrences($xpath);
        $expectedResult = array(
            array(
                'value_found' => 'crazy',
                'count' => 1,
                'context' => 'SiteMaster crazy testing site'
            ),
            array(
                'value_found' => 'insane',
                'count' => 1,
                'context' => "This insane site just contains a few pages to help with integration testing of SiteMaster"
            )
        );

        $this->assertEquals(count($expectedResult), count($result));

        $this->assertEquals($expectedResult[0]['value_found'], $result[0]['value_found']);
        $this->assertEquals($expectedResult[0]['count'], $result[0]['count']);
        $this->assertEquals($expectedResult[0]['context'], $result[0]['context']);

        $this->assertEquals($expectedResult[1]['value_found'], $result[1]['value_found']);
        $this->assertEquals($expectedResult[1]['count'], $result[1]['count']);
        $this->assertEquals(trim($expectedResult[1]['context']), trim($result[1]['context']));
    }

    public function getTestXPath($filename)
    {
        $parser = new HTMl5();
        $html = file_get_contents(__DIR__ . '/data/' . $filename);
        return $parser->parse($html);
    }
}
