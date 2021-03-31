# Sensitive Terms Metric
This SiteMaster plugin reports any sensitive words and terms found during the site scan based
on the list of terms used.  This is simply a report of terms found and will not
affect the grade/score of the scan.

## Configuration
You may define a custom list of terms to using the `terms_list_file` configuration setting to set the CSV file to use.
The file must be a CSV and be added to the plugins `src/terms_list` directory.  If the file is not specified, the
`default.csv` will be used.  The default file should not be edited unless it needs to be updated for the plugins GIT repo.

Do not use any other SiteMaster configuration variables as they are not intended for this plugin.
### Configuration Example
```
'metric_sensitive_terms' => array(
  'terms_list_file' => 'custom.csv',
  'terms_help_document_name' => 'Name of terms help document or style guide',
  'terms_help_document_url' => 'https://url-to-terms-help-document'
)
```
## BSD License Agreement

The software accompanying this license is available to you under the BSD 3-Clause License. See LICENSE for details.
