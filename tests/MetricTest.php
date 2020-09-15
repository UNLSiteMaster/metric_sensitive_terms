<?php
namespace SiteMaster\Plugins\Metric_Sensitive_Terms;

use SiteMaster\Core\Auditor\Parser\HTML5;

class MetricTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function getHTMLVersion()
    {
        $metric = new Metric('unl');

        $xpath = $this->getTestXPath('sensitive_terms.html');
        $results = array();
        $this->assertEquals($results, $metric->getSensitiveTermsOccurrences($xpath));
    }

    public function getTestXPath($filename)
    {
        $parser = new HTMl5();
        $html = file_get_contents(__DIR__ . '/data/' . $filename);
        return $parser->parse($html);
    }
}