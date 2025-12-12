# Test Reports Directory

This directory contains automated test reports for the Open Overheid platform.

## 📁 Contents

- **Test Reports**: Timestamped test execution reports
- **Feature Status**: Detailed feature status documentation
- **Test Generator**: Script to generate new test reports

## 📊 Generating Test Reports

### Automatic Generation

Run the test report generator:

```bash
php guides/test/generate-test-report.php
```

This will:
1. Execute all Feature tests
2. Capture test results
3. Generate a timestamped report: `test-report-YYYY-MM-DD_HH-MM-SS.md`
4. Save it in this directory

### Manual Generation

You can also run tests manually and create reports:

```bash
# Run tests
php artisan test --testsuite=Feature > test-output.txt

# Review output and create report manually
```

## 📋 Report Format

Each test report includes:

- **Test Results Summary**: Total, passing, failing, skipped counts
- **Status**: Overall test status
- **Raw Test Output**: Complete test execution output
- **Analysis**: Breakdown of results
- **Next Steps**: Recommendations for improvement

## 📈 Tracking Progress

Compare reports over time to track:
- Test coverage improvements
- Feature implementation progress
- Bug fixes and stability improvements

## 🔍 Report Naming

Reports are named with timestamps:
- Format: `test-report-YYYY-MM-DD_HH-MM-SS.md`
- Example: `test-report-2025-12-20_14-30-45.md`

This allows chronological tracking of test results.

## 📚 Related Documentation

- **Feature Status**: `FEATURE_STATUS_REPORT.md` - Detailed feature status
- **Missing Features**: `../missing-features-analysis.md` - Missing features list
- **Test Summary**: `TESTING_SUMMARY.md` - Quick reference
- **Test Results**: `TEST_RESULTS.md` - Test results documentation

## 🚀 Usage

### Generate Report Now

```bash
php guides/test/generate-test-report.php
```

### View Latest Report

```bash
ls -t guides/test/test-report-*.md | head -1 | xargs cat
```

### Compare Reports

```bash
# View two most recent reports
ls -t guides/test/test-report-*.md | head -2 | xargs diff
```

---

**Directory:** `guides/test/`  
**Purpose:** Test report storage and generation  
**Last Updated:** 2025-12-20


