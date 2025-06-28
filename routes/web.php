<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
// use App\Http\Controllers\Resource\ProfileController;
use App\Http\Controllers\Resource\BookingController;
use App\Http\Controllers\Resource\BookingRequestController;
use App\Http\Controllers\Resource\RescheduleTicketController;
use App\Http\Controllers\Resource\AnnouncementController;
use App\Http\Controllers\Resource\UserController;
use App\Http\Controllers\Resource\ClassController;
use App\Http\Controllers\Page\All\NotificationPageController;
use App\Http\Controllers\Page\All\RoleSwitchPageController;
use App\Http\Controllers\Page\All\ProfilePageController;
use App\Http\Controllers\Page\Student\BookingPageController as StudentBookingPageController;
use App\Http\Controllers\Page\Student\ReschedulePageController as StudentReschedulePageController;
use App\Http\Controllers\Page\Instructor\ClassPageController as InstructorClassPageController;
use App\Http\Controllers\Page\Instructor\AttendancePageController;
use App\Http\Controllers\Page\Instructor\AnalyticsPageController;
use App\Http\Controllers\Page\Admin\ClassPageController as AdminClassPageController;
use App\Http\Controllers\Page\Admin\ReschedulePageController as AdminReschedulePageController;
use App\Http\Controllers\Page\Admin\UserPageController;

// Auth scaffolding
require __DIR__.'/auth.php';

// Landing
Route::get('/', fn () => view('auth.login'))->name('home');


// Authenticated user actions
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfilePageController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfilePageController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfilePageController::class, 'destroy'])->name('profile.destroy');

    // Role Switching
    Route::post('/switch-role', [RoleSwitchPageController::class, 'switch'])->name('switch.role');
    Route::get('/switch-role/{role}', [RoleSwitchPageController::class, 'switchRole'])->name('switch.role.get');

    // Logout
    Route::get('/logout', function () {
        Auth::logout();
        return redirect('/');
    })->name('logout');
});

// Dashboards
Route::middleware(['auth', 'is_student'])->get('/student/dashboard', [StudentBookingPageController::class, 'showBookNowPage'])->name('student.dashboard');
Route::middleware(['auth', 'is_instructor'])->get('/instructor/dashboard', [InstructorClassPageController::class, 'index'])->name('instructor.dashboard');
Route::middleware(['auth', 'is_admin'])->get('/admin/dashboard', [BookingRequestController::class, 'index'])->name('admin.dashboard');

// Student Routes
Route::middleware(['auth', 'is_student'])->prefix('student')->group(function () {
    Route::get('/book-now', [StudentBookingPageController::class, 'showBookNowPage'])->name('student.book_now');
    Route::get('/my-bookings', [StudentBookingPageController::class, 'myBookings'])->name('student.myBookings');
    Route::get('/my-reschedules', [StudentReschedulePageController::class, 'myReschedules'])->name('student.my_reschedules');

    Route::post('/bookings/book-now/{class}', [BookingController::class, 'bookNow'])->name('student.bookings.bookNow');
    Route::post('/reschedule/{booking}', [BookingController::class, 'requestReschedule'])->name('student.requestReschedule');
    Route::post('/check-reschedule/{class}', [BookingController::class, 'checkRescheduleEligibility'])->name('student.checkReschedule');
});

// Instructor Routes
Route::middleware(['auth', 'is_instructor'])->prefix('instructor')->group(function () {

    Route::get('/classes', [InstructorClassPageController::class, 'index'])->name('instructor.classes.index');
    Route::get('/classes/create', [InstructorClassPageController::class, 'create'])->name('instructor.classes.create');
    Route::get('/classes/{id}/edit', [InstructorClassPageController::class, 'edit'])->name('instructor.classes.edit');
    Route::post('/classes', [ClassController::class, 'store'])->name('instructor.classes.store');
    Route::delete('/classes/{id}', [ClassController::class, 'destroy'])->name('instructor.classes.destroy');
    Route::put('/classes/{id}', [ClassController::class, 'update'])->name('instructor.classes.update');
    

    Route::get('/attendance/selector', [AttendancePageController::class, 'attendanceSelector'])->name('instructor.attendance.selector');
    Route::get('/attendance/selector/{id}', [AttendancePageController::class, 'attendance'])->name('instructor.attendance');
    Route::post('/attendance/class/{id}/save', [AttendancePageController::class, 'saveAttendance'])
    ->name('attendance.class.save');


    Route::get('/attendance/class/{id}', [AttendancePageController::class, 'form'])->name('instructor.attendance.form');
    Route::post('/attendance/class/{id}', [AttendancePageController::class, 'store'])->name('instructor.attendance.store');

    // Route::get('/history', [InstructorClassPageController::class, 'history'])->name('instructor.history');
    Route::get('/history', [ClassController::class, 'index'])->name('instructor.history');

    Route::put('/history/{id}/docs', [ClassController::class, 'updateDocs'])->name('instructor.history.updateDocs');
    Route::get('/attendance-analytics', [AnalyticsPageController::class, 'attendanceAnalytics'])->name('instructor.analytics');
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'is_admin'])->group(function () {
    // Booking requests
    Route::get('/booking-requests', [BookingRequestController::class, 'index'])->name('admin.bookingRequests');
    Route::post('/booking-requests/{id}/status/{status}', [BookingRequestController::class, 'updateStatus'])->name('admin.bookingRequests.updateStatus');

    // Reschedule tickets
    Route::get('/reschedule-tickets', [AdminReschedulePageController::class, 'index'])->name('admin.reschedule_tickets');
    Route::post('/reschedule-tickets/delete-expired', [RescheduleTicketController::class, 'deleteExpired'])->name('admin.reschedule_tickets.delete_expired');
    Route::post('/reschedule-tickets/{ticket}/use', [RescheduleTicketController::class, 'useTicket'])->name('admin.reschedule_tickets.use');

    // Class management
    Route::get('/classes', [InstructorClassPageController::class, 'index'])->name('admin.manage_classes');
    Route::get('/classes/create', [AdminClassPageController::class, 'create'])->name('admin.classes.create');
    Route::get('/classes/{id}/edit', [AdminClassPageController::class, 'edit'])->name('admin.classes.edit');


    Route::post('/classes', [ClassController::class, 'store'])->name('admin.classes.store');
    Route::delete('/classes/{id}', [ClassController::class, 'destroy'])->name('admin.classes.destroy');
    Route::put('/classes/{id}', [ClassController::class, 'update'])->name('admin.manage_classes.update');





    // Notifications
    Route::get('/notifications', [AnnouncementController::class, 'index'])->name('admin.notifications');
    Route::post('/notifications', [AnnouncementController::class, 'store'])->name('admin.notifications.store');
    Route::put('/notifications/{id}', [AnnouncementController::class, 'update'])->name('admin.notifications.update');
    Route::delete('/notifications/{id}', [AnnouncementController::class, 'destroy'])->name('admin.notifications.destroy');

    // User management
    Route::get('/manage-users', [UserPageController::class, 'index'])->name('admin.users.index');
    Route::put('/manage-users/{id}/roles', [UserController::class, 'updateRoles'])->name('admin.users.updateRoles');
    Route::delete('/manage-users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    //getting analytics(the same iwht instructors)
    Route::get('/reports/analytics', [AnalyticsPageController::class, 'attendanceAnalytics'])->name('admin.reports.analytics');

});

// Notifications (shared)
Route::prefix('notifications')->middleware('auth')->group(function () {
    Route::get('/notifications/announcements', [AnnouncementController::class, 'index'])
    ->name('notifications.announcements');
    Route::get('/history', [NotificationPageController::class, 'history'])->name('notifications.history');
});
