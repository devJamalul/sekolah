<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\AcademicYear;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(10)->by($email . $request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(10)->by($request->session()->get('login.id'));
        });

        Fortify::loginView(function () {
            return view('pages.login');
        });

        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();

            if (
                $user &&
                Hash::check($request->password, $user->password)
            ) {
                if (!is_null($user->school_id)) {
                    session(['school_id' => $user->school_id]);
                }

                $academicYear = AcademicYear::active()->first();

                if (!is_null($academicYear)) {
                    session(['academic_year_id' => $academicYear->id]);
                    session(['academic_year_name' => $academicYear->academic_year_name ]);
                }

                $ppdb = AcademicYear::PPDB()->first();

                if (!is_null($ppdb)) {
                    session(['ppdb_academic_year_id' => $ppdb->id]);
                    session(['ppdb_academic_year_name' => $ppdb->academic_year_name]);
                }

                return $user;
            }
        });
    }
}
