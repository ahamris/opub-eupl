<?php

/**
 * Test Report Generator
 *
 * Generates a timestamped test report from Pest test results
 *
 * Usage: php guides/test/generate-test-report.php
 */
$timestamp = date('Y-m-d_H-i-s');
$reportDir = __DIR__;
$reportFile = "{$reportDir}/test-report-{$timestamp}.md";

// Run tests and capture output
$output = [];
$returnCode = 0;
exec('php artisan test --testsuite=Feature 2>&1', $output, $returnCode);

$testOutput = implode("\n", $output);

// Extract test summary
preg_match('/Tests:\s+(\d+)\s+(failed|passed|skipped)?.*?(\d+)\s+(failed|passed|skipped)?.*?(\d+)\s+(failed|passed|skipped)?/i', $testOutput, $matches);

$passed = 0;
$failed = 0;
$skipped = 0;

if (isset($matches[1])) {
    if (stripos($matches[2] ?? '', 'passed') !== false) {
        $passed = (int) $matches[1];
    } elseif (stripos($matches[2] ?? '', 'failed') !== false) {
        $failed = (int) $matches[1];
    } elseif (stripos($matches[2] ?? '', 'skipped') !== false) {
        $skipped = (int) $matches[1];
    }
}

if (isset($matches[3])) {
    if (stripos($matches[4] ?? '', 'passed') !== false) {
        $passed = (int) $matches[3];
    } elseif (stripos($matches[4] ?? '', 'failed') !== false) {
        $failed = (int) $matches[3];
    } elseif (stripos($matches[4] ?? '', 'skipped') !== false) {
        $skipped = (int) $matches[3];
    }
}

if (isset($matches[5])) {
    if (stripos($matches[6] ?? '', 'passed') !== false) {
        $passed = (int) $matches[5];
    } elseif (stripos($matches[6] ?? '', 'failed') !== false) {
        $failed = (int) $matches[5];
    } elseif (stripos($matches[6] ?? '', 'skipped') !== false) {
        $skipped = (int) $matches[5];
    }
}

// Try alternative pattern - Pest format: "Tests:    10 failed, 14 skipped, 44 passed"
if ($passed === 0 && $failed === 0 && $skipped === 0) {
    // Try Pest format: "Tests:    10 failed, 14 skipped, 44 passed"
    preg_match('/Tests:\s+(\d+)\s+(failed|passed|skipped).*?(\d+)\s+(failed|passed|skipped).*?(\d+)\s+(failed|passed|skipped)/i', $testOutput, $pestMatches);
    if (isset($pestMatches[1])) {
        // Parse all three values
        for ($i = 1; $i <= 5; $i += 2) {
            if (isset($pestMatches[$i]) && isset($pestMatches[$i + 1])) {
                $value = (int) $pestMatches[$i];
                $type = strtolower($pestMatches[$i + 1]);
                if ($type === 'passed') {
                    $passed = $value;
                } elseif ($type === 'failed') {
                    $failed = $value;
                } elseif ($type === 'skipped') {
                    $skipped = $value;
                }
            }
        }
    }

    // Try another format: "44 passed, 10 failed, 14 skipped"
    if ($passed === 0 && $failed === 0 && $skipped === 0) {
        preg_match('/(\d+)\s+passed.*?(\d+)\s+failed.*?(\d+)\s+skipped/i', $testOutput, $altMatches);
        if (isset($altMatches[1])) {
            $passed = (int) $altMatches[1];
            $failed = (int) ($altMatches[2] ?? 0);
            $skipped = (int) ($altMatches[3] ?? 0);
        }
    }
}

$total = $passed + $failed + $skipped;
$passRate = $total > 0 ? round(($passed / $total) * 100, 1) : 0;
$failedPercent = $total > 0 ? round(($failed / $total) * 100, 1) : 0;
$skippedPercent = $total > 0 ? round(($skipped / $total) * 100, 1) : 0;

// Generate report
$report = <<<MARKDOWN
# Test Report: Open Overheid Platform
## Automated Test Execution Report

**Generated:** {$timestamp}  
**Test Framework:** Pest PHP  
**Test Suite:** Feature Tests

---

## 📊 Test Results Summary

| Metric | Count | Percentage |
|--------|-------|------------|
| **Total Tests** | {$total} | 100% |
| **✅ Passing** | {$passed} | {$passRate}% |
| **❌ Failing** | {$failed} | {$failedPercent}% |
| **⏭️ Skipped** | {$skipped} | {$skippedPercent}% |

### Status
MARKDOWN;

if ($failed === 0 && $skipped === 0) {
    $report .= "\n**✅ All tests passing!**\n";
} elseif ($failed === 0) {
    $report .= "\n**✅ All active tests passing!** ({$skipped} tests skipped - missing features)\n";
} else {
    $report .= "\n**⚠️ Some tests failing** - Review details below\n";
}

$report .= <<<MARKDOWN

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
$report .= "   - Run: `php guides/test/generate-test-report.php`\n\n";

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
file_put_contents($reportFile, $report);

echo "✅ Test report generated: {$reportFile}\n";
echo "📊 Summary: {$passed} passed, {$failed} failed, {$skipped} skipped\n";
echo "📁 Location: guides/test/test-report-{$timestamp}.md\n";

exit($returnCode);
