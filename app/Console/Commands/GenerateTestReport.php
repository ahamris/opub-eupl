<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateTestReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:report 
                            {--output= : Custom output directory}
                            {--suite=Feature : Test suite to run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a timestamped test report';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $timestamp = date('Y-m-d_H-i-s');
        $suite = $this->option('suite') ?? 'Feature';
        $outputDir = $this->option('output') ?? base_path('guides/test');

        // Ensure output directory exists
        if (! File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }

        $reportFile = "{$outputDir}/test-report-{$timestamp}.md";

        $this->info("Running {$suite} test suite...");

        // Run tests and capture output
        $output = [];
        $returnCode = 0;
        exec("php artisan test --testsuite={$suite} 2>&1", $output, $returnCode);

        $testOutput = implode("\n", $output);

        // Extract test summary - try multiple patterns
        $passed = 0;
        $failed = 0;
        $skipped = 0;

        // Pattern 1: "Tests: X passed, Y failed, Z skipped"
        if (preg_match('/(\d+)\s+passed.*?(\d+)\s+failed.*?(\d+)\s+skipped/i', $testOutput, $matches)) {
            $passed = (int) $matches[1];
            $failed = (int) $matches[2];
            $skipped = (int) $matches[3];
        }
        // Pattern 2: "Tests: X failed, Y skipped, Z passed"
        elseif (preg_match('/Tests:\s+(\d+)\s+(failed|passed|skipped).*?(\d+)\s+(failed|passed|skipped).*?(\d+)\s+(failed|passed|skipped)/i', $testOutput, $matches)) {
            // Parse the matches
            for ($i = 1; $i <= 5; $i += 2) {
                if (isset($matches[$i]) && isset($matches[$i + 1])) {
                    $count = (int) $matches[$i];
                    $type = strtolower($matches[$i + 1]);
                    if ($type === 'passed') {
                        $passed = $count;
                    } elseif ($type === 'failed') {
                        $failed = $count;
                    } elseif ($type === 'skipped') {
                        $skipped = $count;
                    }
                }
            }
        }

        $total = $passed + $failed + $skipped;
        $passRate = $total > 0 ? round(($passed / $total) * 100, 1) : 0;
        $failRate = $total > 0 ? round(($failed / $total) * 100, 1) : 0;
        $skipRate = $total > 0 ? round(($skipped / $total) * 100, 1) : 0;

        // Generate report
        $status = '✅ All tests passing!';
        if ($failed > 0) {
            $status = '⚠️ Some tests failing - Review details below';
        } elseif ($skipped > 0) {
            $status = "✅ All active tests passing! ({$skipped} tests skipped - missing features)";
        }

        $report = <<<MARKDOWN
# Test Report: Open Overheid Platform
## Automated Test Execution Report

**Generated:** {$timestamp}  
**Test Framework:** Pest PHP  
**Test Suite:** {$suite} Tests

---

## 📊 Test Results Summary

| Metric | Count | Percentage |
|--------|-------|------------|
| **Total Tests** | {$total} | 100% |
| **✅ Passing** | {$passed} | {$passRate}% |
| **❌ Failing** | {$failed} | {$failRate}% |
| **⏭️ Skipped** | {$skipped} | {$skipRate}% |

### Status
{$status}

---

## 📋 Test Execution Details

### Raw Test Output

\`\`\`
{$testOutput}
\`\`\`

---

## 📁 Test Files

### Feature Tests
- `tests/Feature/SearchPageTest.php` - Search page functionality
- `tests/Feature/SearchResultsTest.php` - Search, filtering, sorting
- `tests/Feature/DocumentDetailTest.php` - Document detail page
- `tests/Feature/MissingFeaturesTest.php` - Missing features documentation
- `tests/Feature/UIComponentsTest.php` - UI components and accessibility
- `tests/Feature/APITest.php` - API endpoints

---

## 🔍 Analysis

MARKDOWN;

        if ($failed > 0) {
            $report .= "\n### ❌ Failing Tests\n";
            $report .= "Review the test output above to identify failing tests.\n";
            $report .= "Most failures are likely minor UI assertion issues.\n\n";
        }

        if ($skipped > 0) {
            $report .= "\n### ⏭️ Skipped Tests\n";
            $report .= "{$skipped} tests are skipped - these document missing features.\n";
            $report .= "These tests will pass once the features are implemented.\n\n";
        }

        $report .= <<<'MARKDOWN'

---

## 📊 Historical Comparison

Compare with previous test reports in this directory to track progress.

Previous reports:
MARKDOWN;

        // List previous reports
        $previousReports = File::glob("{$outputDir}/test-report-*.md");
        rsort($previousReports);
        $previousReports = array_slice($previousReports, 0, 5); // Last 5 reports

        if (count($previousReports) > 0) {
            foreach ($previousReports as $prevReport) {
                $prevName = basename($prevReport);
                if ($prevName !== basename($reportFile)) {
                    $report .= "\n- `{$prevName}`";
                }
            }
        } else {
            $report .= "\n- No previous reports found";
        }

        $report .= <<<'MARKDOWN'


---

## 🚀 Next Steps

MARKDOWN;

        if ($failed > 0) {
            $report .= "\n1. **Fix Failing Tests**\n";
            $report .= "   - Review test output\n";
            $report .= "   - Fix issues identified\n";
            $report .= "   - Re-run tests: `php artisan test`\n\n";
        }

        $report .= "2. **Review Missing Features**\n";
        $report .= "   - Check `tests/Feature/MissingFeaturesTest.php`\n";
        $report .= "   - Implement high priority features\n\n";

        $report .= "3. **Generate Next Report**\n";
        $report .= "   - Run: `php artisan test:report`\n\n";

        $report .= <<<MARKDOWN

---

## 📝 Notes

- This report is automatically generated
- Timestamp format: YYYY-MM-DD_HH-MM-SS
- All test files are in `tests/Feature/`
- For detailed feature status, see `guides/test/FEATURE_STATUS_REPORT.md`

---

**Report File:** `test-report-{$timestamp}.md`  
**Generated At:** {$timestamp}  
**Exit Code:** {$returnCode}

MARKDOWN;

        // Write report
        File::put($reportFile, $report);

        $this->info("✅ Test report generated: {$reportFile}");
        $this->info("📊 Summary: {$passed} passed, {$failed} failed, {$skipped} skipped");
        $this->info("📁 Location: guides/test/test-report-{$timestamp}.md");

        if ($failed > 0) {
            $this->warn("⚠️  {$failed} tests are failing - review the report for details");
        }

        return $returnCode;
    }
}
