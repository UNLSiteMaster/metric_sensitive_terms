<?php
namespace SiteMaster\Plugins\Metric_Sensitive_Terms;

use SiteMaster\Core\Auditor\Logger\Metrics;
use SiteMaster\Core\Auditor\MetricInterface;
use SiteMaster\Core\Exception;
use SiteMaster\Core\Registry\Site;
use SiteMaster\Core\Auditor\Scan;
use SiteMaster\Core\Auditor\Site\Page;
use SiteMaster\Core\RuntimeException;

class Metric extends MetricInterface
{
    private $sensitiveTerms = array();
    private $sensitveTermsFile = 'default.csv';

    /**
     * @param string $plugin_name
     * @param array $options
     */
    public function __construct($plugin_name, array $options = array())
    {
        if (!empty($options['terms_list_file'])) {
            $this->sensitveTermsFile = $options['terms_list_file'];
        }

        $this->setTermsFromCSV();

        parent::__construct($plugin_name, $options);
    }

    /**
     * Get the human readable name of this metric
     *
     * @return string The human readable name of the metric
     */
    public function getName()
    {
        return 'Sensitive Terms Report';
    }

    /**
     * Get the Machine name of this metric
     *
     * This is what defines this metric in the database
     *
     * @return string The unique string name of this metric
     */
    public function getMachineName()
    {
        return 'sensitive_terms';
    }

    /**
     * Determine if this metric should be graded as pass-fail
     *
     * @return bool True if pass-fail, False if normally graded
     */
    public function isPassFail()
    {
        return true;
    }

    /**
     * Scan a given URI and apply all marks to it.
     *
     * All that this
     *
     * @param string $uri The uri to scan
     * @param \DOMXPath $xpath The xpath of the uri
     * @param int $depth The current depth of the scan
     * @param \SiteMaster\Core\Auditor\Site\Page $page The current page to scan
     * @param \SiteMaster\Core\Auditor\Logger\Metrics $context The logger class which calls this method, you can access the spider, page, and scan from this
     * @throws \Exception
     * @return bool True if there was a successful scan, false if not.  If false, the metric will be graded as incomplete
     */
    public function scan($uri, \DOMXPath $xpath, $depth, Page $page, Metrics $context)
    {
        $occurrences = $this->getSensitiveTermsOccurrences($xpath);
        $mark = $this->getMark(
            $this->getMachineName(),
            'A sensitive term was found.',
            0,
            'Verify if word is appropriate for its context, and replace if not.',
            'See [the WDN style guide documentation](https://wdn.unl.edu/unl-web-framework-5-standards-guide) for help on this topic.',
            true
        );
        foreach ($occurrences as $occurrence) {
            $page->addMark($mark, array(
                'value_found' => $occurrence['value_found'],
                'context'     => $occurrence['context']
            ));
        }
        return true;
    }

    /**
     * Get a list of instances of sensitive terms in the text of the document
     *
     * @param \DomXpath $xpath
     * @return array - an array of the sensitive term textual references. Each is an associative array with 'context', 'value_found' values
     */
    public function getSensitiveTermsOccurrences(\DomXpath $xpath)
    {
        $errors = array();
        if (count($this->sensitiveTerms) === 0) {
            return $errors;
        }

        $nodes = $xpath->query("//*//text()");
        foreach ($nodes as $node) {
            foreach ($this->sensitiveTerms as $sensitiveTerm) {
                $termFoundCount = substr_count(strtolower($node->textContent), strtolower($sensitiveTerm));
                if ($termFoundCount > 0) {
                    $errors[] = array(
                        'value_found' => $sensitiveTerm,
                        'count' => $termFoundCount,
                        'context' => $node->textContent
                    );
                }
            }
        }

        return $errors;
    }

    private function setTermsFromCSV() {
        $file = __DIR__ . '/terms_list/' . $this->sensitveTermsFile;
        $this->sensitiveTerms = array();
        try {
            if (file_exists($file) && ($handle = fopen($file, "r")) !== FALSE) {
                while (($rowData = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    foreach ($rowData as $data) {
                        if (!empty(trim($data))) {
                            $this->sensitiveTerms[] = trim($data);
                        }
                    }
                }
                fclose($handle);
            }
        } catch (Exception $e) {
            // Do Nothing
        }
    }
}
