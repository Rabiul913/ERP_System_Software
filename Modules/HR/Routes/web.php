<?php

use Illuminate\Support\Facades\Route;
use Modules\HR\Http\Controllers\AllowanceController;
use Modules\HR\Http\Controllers\AllowanceTypeController;
use Modules\HR\Http\Controllers\BankController;
use Modules\HR\Http\Controllers\LineController;
use Modules\HR\Http\Controllers\UnitController;
use Modules\HR\Http\Controllers\FloorController;
use Modules\HR\Http\Controllers\GradeController;
use Modules\HR\Http\Controllers\ShiftController;
use Modules\HR\Http\Controllers\GenderController;
use Modules\HR\Http\Controllers\BusStopController;
use Modules\HR\Http\Controllers\HolidayController;
use Modules\HR\Http\Controllers\PayModeController;
use Modules\HR\Http\Controllers\SectionController;
use Modules\HR\Http\Controllers\DistrictController;
use Modules\HR\Http\Controllers\ReligionController;
use Modules\HR\Http\Controllers\DepartmentController;
use Modules\HR\Http\Controllers\PostOfficeController;
use Modules\HR\Http\Controllers\SubSectionController;
use Modules\HR\Http\Controllers\BuidingInfoController;
use Modules\HR\Http\Controllers\DesignationController;
use Modules\HR\Http\Controllers\ReleasedTypeController;
use Modules\HR\Http\Controllers\PoliceStationController;
use Modules\HR\Http\Controllers\SalarySettingController;
use Modules\HR\Http\Controllers\BankBranchInfoController;
use Modules\HR\Http\Controllers\EmployeeIncrementController;
use Modules\HR\Http\Controllers\EmployeeMasterController;
use Modules\HR\Http\Controllers\EmployeeReleaseController;
use Modules\HR\Http\Controllers\EmployeeSalaryController;
use Modules\HR\Http\Controllers\EmployeeTransferController;
use Modules\HR\Http\Controllers\EmployeeShiftEntryController;
use Modules\HR\Http\Controllers\LeaveTypeController;
use Modules\HR\Http\Controllers\LeaveBalanceController;
use Modules\HR\Http\Controllers\LeaveEntryController;
use Modules\HR\Http\Controllers\FixAttendanceController;
use Modules\HR\Http\Controllers\SalaryAdjustmentController;
use Modules\HR\Http\Controllers\AttendanceReportController;
use Modules\HR\Http\Controllers\BonusController;
use Modules\HR\Http\Controllers\BonusSettingController;
use Modules\HR\Http\Controllers\ProcessedAttendanceController;
use Modules\HR\Http\Controllers\ProcessedSalaryController;
use Modules\HR\Http\Controllers\HRReportController;
use Modules\HR\Http\Controllers\ProcessedBonusController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('hr')->group(function () {
    Route::get('/', 'hrController@index');

    Route::middleware(['auth'])->group(function () {
        Route::resources([
            'allowances' => AllowanceController::class,
            'allowance-types' => AllowanceTypeController::class,
            'bonuses' => BonusController::class,
            'bonus-settings' => BonusSettingController::class,
            'sections' => SectionController::class,
            'departments' => DepartmentController::class,
            'sub-sections' => SubSectionController::class,
            'banks' => BankController::class,
            'bank-branch-info' => BankBranchInfoController::class,
            'building-infos' => BuidingInfoController::class,
            'bus-stops' => BusStopController::class,
            'districts' => DistrictController::class,
            'floors' => FloorController::class,
            'lines' => LineController::class,
            'grades' => GradeController::class,
            'designations' => DesignationController::class,
            'police-stations' => PoliceStationController::class,
            'post-offices' => PostOfficeController::class,
            'units' => UnitController::class,
            'shifts' => ShiftController::class,
            'religions' => ReligionController::class,
            'employee-types' => EmployeeTypeController::class,
            'salary-settings' => SalarySettingController::class,
            'employee-masters' => EmployeeMasterController::class,
            'pay-modes' => PayModeController::class,
            'genders' => GenderController::class,
            'released-types' => ReleasedTypeController::class,
            'employee-salaries' => EmployeeSalaryController::class,
            'employee-releases' => EmployeeReleaseController::class,
            'holidays' => HolidayController::class,
            'employee-increments' => EmployeeIncrementController::class,
            'employee-transfers' => EmployeeTransferController::class,
            'employee-shifts' => EmployeeShiftEntryController::class,
            'leave-types' => LeaveTypeController::class,
            'leave-balances' => LeaveBalanceController::class,
            'leave-entries' => LeaveEntryController::class,
            'fix-attendances' => FixAttendanceController::class,
            'process-salary' => ProcessedSalaryController::class,
            'salary-adjustments' => SalaryAdjustmentController::class,
            'bonus-process' => ProcessedBonusController::class,
        ]);
    });

    Route::post('/fetch-divisions', [DistrictController::class, 'fetchDivisions'])->name('fetchDivisions');
    Route::post('/fetch-districts', [DistrictController::class, 'fetchDistricts'])->name('fetchDistricts');
    Route::post('/fetch-police-stations', [PoliceStationController::class, 'fetchPoliceStation'])->name('fetchPoliceStation');
    Route::post('/fetch-post-office', [PostOfficeController::class, 'fetchPostOffice'])->name('fetchPostOffice');
    Route::post('/fetch-bank-branches', [BankBranchInfoController::class, 'fetchBankBranch'])->name('fetchBankBranch');

    Route::post('/get-employee-type-salary', [EmployeeSalaryController::class, 'getEmployeeTypeSalary'])->name('getEmployeeTypeSalary');
    Route::post('/get-single-employee-increment-salary', [EmployeeIncrementController::class, 'getSingleEmployeeIncrementSalary'])->name('getSingleEmployeeIncrementSalary');
    Route::post('/get-single-employee-transfer', [EmployeeTransferController::class, 'getSingleEmployeeTransfer'])->name('getSingleEmployeeTransfer');
    Route::post('/get-employee-leave', [LeaveEntryController::class, 'getEmployeeLeaves'])->name('getEmployeeLeaves');
    Route::get('employee-leave/leave-approve/{id}', [LeaveEntryController::class, 'approve'])->name('leave-entries.leave-approve');
    Route::post('employee-leave/leave-reject', [LeaveEntryController::class, 'reject'])->name('leave-entries.leave-reject');
    Route::post('get-date-wise-attendance', [FixAttendanceController::class, 'getDatewiseAttendance'])->name('getDatewiseAttendance');
    Route::post('create-single-fix-attendance', [FixAttendanceController::class, 'singleInsertOrUpdate'])->name('singleInsertOrUpdate');
    Route::get('processed/attendances', [FixAttendanceController::class, 'index_processed'])->name('processed-attendances.index');
    Route::post('attendance/processed', [ProcessedAttendanceController::class, 'processedAttendances'])->name('attendances.processed');
    Route::post('salary/processed', [ProcessedSalaryController::class, 'processedSalary'])->name('salary.processed');
    Route::post('getHolidayOrLeave', [HolidayController::class, 'getHolidayOrLeave'])->name('getHolidayOrLeave');

    Route::post('getHoliday', [HolidayController::class, 'getHoliday'])->name('holiday.date');


    // Route::get('attendances/late/report', [AttendanceReportController::class, 'lateReportIndex'])->name('reports.late');
    // Route::post('attendances/getlate/report/', [AttendanceReportController::class, 'getLateReportData'])->name('getlatedata');

    Route::get('employee-list', [HRReportController::class, 'employeeList'])->name('employeeList');
    Route::post('employee-list/report', [HRReportController::class, 'employeeListReport'])->name('employeeList.report');

    Route::get('promotion-increment-list', [HRReportController::class, 'promotionIncrementList'])->name('promotionIncrementList');
    Route::post('promotion-increment-list/report', [HRReportController::class, 'promotionIncrementListReport'])->name('promotionIncrementList.report');

    Route::get('daily-attendance', [HRReportController::class, 'dailyAttendance'])->name('dailyAttendance');
    Route::post('daily-attendance-list/report', [HRReportController::class, 'dailyAttendanceReport'])->name('dailyAttendance.report');

    Route::get('attendance-summary', [HRReportController::class, 'attendanceSummary'])->name('attendanceSummary');
    Route::post('attendance-summary/report', [HRReportController::class, 'attendanceSummaryReport'])->name('attendanceSummary.report');

    Route::get('job-card', [HRReportController::class, 'jobCard'])->name('jobCard');
    Route::post('job-card/report', [HRReportController::class, 'jobCardReport'])->name('jobCard.report');

    Route::get('leave-report', [HRReportController::class, 'leaveReportIndex'])->name('leaveReport');
    Route::post('leave-report/report', [HRReportController::class, 'leaveReport'])->name('leaveReport.report');

    Route::get('ot-sheet', [HRReportController::class, 'otSheet'])->name('otSheet');
    Route::post('ot-sheet/report', [HRReportController::class, 'otSheetReport'])->name('otSheet.report');

    Route::get('allowance-report', [HRReportController::class, 'allowanceReportIndex'])->name('allowanceReportIndex');
    Route::post('allowance-report/report', [HRReportController::class, 'allowanceReport'])->name('allowanceReport');

    Route::get('salary-sheet', [HRReportController::class, 'salarySheet'])->name('salarySheet');
    Route::post('salary-sheet/report', [HRReportController::class, 'salarySheetReport'])->name('salarySheet.report');

    Route::get('bonus-sheet', [HRReportController::class, 'bonusSheet'])->name('bonusSheet');
    Route::post('bonus-sheet/report', [HRReportController::class, 'bonusSheetReport'])->name('bonusSheet.report');

    //process bonus
    // Route::get('bonus-process', [ProcessedBonusController::class, 'bonusProcessIndex'])->name('bonusProcess.index');
    // Route::post('bonus-process', [ProcessedBonusController::class, 'bonusProcess'])->name('bonusProcess.process');
    // Route::put('bonus-process/{processed_bonus_id}', [ProcessedBonusController::class, 'bonusProcess.reprocess'])->name('bonusProcess.reprocess');
    // Route::delete('bonus-process/{processed_bonus_id}', [ProcessedBonusController::class, 'bonusProcess.destroy'])->name('bonusProcess.destroy');


    Route::get('fix-attendance-report', [HRReportController::class, 'fixAttendanceReportIndex'])->name('fixAttendanceReportIndex');
    Route::post('fix-attendance-report/report', [HRReportController::class, 'fixAttendanceReport'])->name('fixAttendanceReport');
    Route::get('pay-slip-report', [HRReportController::class, 'paySlipReportIndex'])->name('paySlipReportIndex');
    Route::post('pay-slip-report/report', [HRReportController::class, 'paySlipReport'])->name('paySlipReport');

    Route::get('get-department-wise-employees', [HRReportController::class, 'getDepartmentWiseEmployees'])->name('getDepartmentWiseEmployees');
    Route::get('get-type-wise-employees', [HRReportController::class, 'getTypeWiseEmployees'])->name('getTypeWiseEmployees');
    Route::get('get-designation-wise-employees', [HRReportController::class, 'getDesignationWiseEmployees'])->name('getDesignationWiseEmployees');
    Route::get('get-shift-wise-employees', [HRReportController::class, 'getShiftWiseEmployees'])->name('getShiftWiseEmployees');
});
